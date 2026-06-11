# Irrigatore V2 — Diseño Fase 0 + Fase 1

**Fecha:** 2026-06-10
**Estado:** Aprobado (brainstorming) — pendiente de revisión del spec escrito
**Alcance de este documento:** Fundaciones de infraestructura (Fase 0) + camino feliz de telemetría de punta a punta (Fase 1). Las Fases 2–4 se describen como roadmap y se especificarán al llegar.

---

## 1. Contexto y objetivos

Irrigatore V2 es una reconstrucción desde cero del sistema de riego automático V1. La V1 era un sistema de tres capas (Arduino C++ → MySQL directo → frontend PHP) con deuda técnica significativa: SQL injection en el firmware, credenciales de DB embebidas en la placa, XSS en el dashboard, código duplicado y la placa escribiendo directo a la base de datos.

**Objetivos de la V2 (los cuatro):**
1. Stack moderno.
2. Producto real y usable.
3. Escalar a múltiples dispositivos.
4. Resolver la deuda técnica de V1.

**Criterio de decisión:** se elige el mejor stack por **mérito técnico**, no por facilidad de aprendizaje.

---

## 2. Stack tecnológico

| Capa | Tecnología |
|---|---|
| Hardware | ESP32 + sensor capacitivo de humedad de suelo + BME280 (temp/humedad/presión) + relé/bomba |
| Firmware | C++ con PlatformIO (framework Arduino) |
| Transporte | MQTT sobre TLS |
| Broker | EMQX (contenedor Docker) |
| Backend | Node.js + TypeScript + Fastify |
| Base de datos | PostgreSQL + TimescaleDB |
| Frontend | React + TypeScript + Vite (SPA) |
| UI | Tailwind + shadcn/ui; gráficos con uPlot |
| Server state (front) | TanStack Query |
| Estado UI (front) | Zustand (mínimo) |
| Routing | React Router |
| Infra | VPS único + Docker Compose, todo contenerizado. All-cloud, sin Raspberry Pi. |

**Decisiones de stack y su justificación técnica:**
- **MQTT/TLS, la placa nunca toca la DB:** elimina el pecado central de V1 (SQL injection + credenciales en firmware). MQTT es bidireccional (permite comandos de riego) y escala a muchos dispositivos.
- **EMQX self-hosted:** broker MQTT más completo (TLS, auth por dispositivo, dashboard de monitoreo), en el mismo Docker Compose.
- **Node.js + TypeScript + Fastify:** el workload de un hub IoT es IO-bound y de alta concurrencia (conexiones MQTT/WebSocket persistentes), punto fuerte del event loop. Tipos compartidos end-to-end con el frontend eliminan una clase entera de bugs de contrato.
- **PostgreSQL + TimescaleDB:** los datos de sensores son series temporales; Timescale aporta compresión automática y continuous aggregates. Un solo motor cubre lo relacional y lo temporal.
- **React + Vite (SPA), no Next.js:** el backend es un proceso separado de larga vida (escucha MQTT 24/7), lo que vacía de sentido las rutas API serverless de Next; el dashboard va detrás de login, sin valor de SSR/SEO.
- **VPS + Docker Compose, sin Pi:** control total, costo fijo bajo. Se evaluó la Raspberry Pi como gateway local (riego autónomo sin internet) y se eligió all-cloud; MQTT deja la puerta abierta a sumar una Pi gateway más adelante sin rehacer nada.

---

## 3. Arquitectura y topología

```
┌─────────────┐   MQTT/TLS    ┌──────────┐   pub/sub   ┌──────────────────┐
│  ESP32 #1   │──────────────▶│          │◀───────────▶│  Backend (Node)  │
│ (zona A)    │◀──────────────│  EMQX    │             │  ├─ MQTT worker  │
├─────────────┤   comandos    │  Broker  │             │  ├─ API REST     │
│  ESP32 #2   │──────────────▶│          │             │  └─ WS server    │
│ (zona B)    │◀──────────────│          │             └────────┬─────────┘
└─────────────┘               └──────────┘                      │
                                                                ▼
        ┌──────────────┐   REST + WebSocket        ┌────────────────────────┐
        │ React SPA    │◀─────────────────────────▶│ PostgreSQL+TimescaleDB │
        │ (dashboard)  │         Backend           │ (series + relacional)  │
        └──────────────┘                           └────────────────────────┘
```

**Flujo en una frase:** la ESP32 publica telemetría por MQTT/TLS → EMQX → un worker del backend la valida, la persiste en TimescaleDB y la reenvía por WebSocket al dashboard en vivo; las reglas de riego o el control manual generan comandos que el backend publica por MQTT de vuelta a la placa.

Todo corre como contenedores Docker en un único VPS, orquestado con Docker Compose: `emqx`, `postgres` (con TimescaleDB), `backend`, `frontend` (servido como estáticos vía Nginx o equivalente).

---

## 4. Descomposición en fases

Cada fase tiene su propio ciclo spec → plan → implementación.

- **Fase 0 — Fundaciones de infra:** monorepo, Docker Compose (EMQX + Postgres/Timescale + backend + frontend), TLS, esquema base de datos, CI.
- **Fase 1 — Camino feliz de telemetría:** firmware ESP32 publica lecturas reales → backend las ingesta y persiste → dashboard las muestra en vivo. Sistema funcionando de punta a punta; hito principal.
- **Fase 2 — Multi-usuario y multi-zona:** auth con cuentas, aislamiento de datos por usuario, provisioning y autenticación por dispositivo, modelo de zonas/placas.
- **Fase 3 — Control y automatización:** control manual remoto + motor de reglas / riego programado.
- **Fase 4 — Recomendaciones + alertas:** portar la recomendación de V1 + sistema de notificaciones (email/push/Telegram).

Este documento detalla **Fase 0 y Fase 1**.

---

## 5. Modelo de datos

### 5.1 Entidades relacionales (Postgres)

```
users        (id, email, password_hash, created_at)
devices      (id, user_id→users, name, device_key, status, fw_version, last_seen_at)
zones        (id, device_id→devices, name, crop_type, created_at)
```

- `device` = una placa ESP32 física; `device_key` es su credencial para autenticarse contra EMQX (nunca credenciales de DB en el firmware).
- `zone` = un punto de riego dentro de una placa (un sensor capacitivo de suelo + una válvula/bomba). Una placa puede manejar varias zonas. El BME280 es ambiental → su lectura es a nivel device.

> **Nota de fases:** la tabla `users` y las FK `user_id` existen desde Fase 0 para no migrar la hypertable después, pero la **auth real (registro/login/aislamiento) es Fase 2**. En Fase 0/1 se opera con un usuario y un device sembrados (seed) para validar el camino de telemetría.

### 5.2 Series temporales (TimescaleDB hypertable) — formato *narrow*

```
readings (hypertable, particionada por time)
  time         timestamptz
  device_id    → devices
  zone_id      → zones        (NULL para métricas ambientales del device)
  metric       text           (soil_moisture | air_temp | air_humidity | pressure)
  value        double precision
```

**Decisión: formato narrow** (una fila por métrica) en vez de wide (columnas por métrica). Razón: agregar un sensor nuevo no cambia el esquema, los continuous aggregates son más limpios, y maneja naturalmente que el suelo sea por-zona y el clima por-device. Sumar futuras métricas (`battery_voltage`, `tank_level`) es cero migración.

---

## 6. Contrato MQTT

### 6.1 Topics

```
irrigatore/{deviceId}/telemetry        ← la placa publica lecturas
irrigatore/{deviceId}/status           ← online/offline (MQTT Last Will & Testament)
irrigatore/{deviceId}/cmd              ← el backend publica comandos (regar zona X) [Fase 3]
irrigatore/{deviceId}/cmd/ack          ← la placa confirma ejecución del comando   [Fase 3]
```

El topic `status` usa el **Last Will & Testament** de MQTT para detección de placa caída (base de las alertas de Fase 4). QoS 1 en telemetría para no perder lecturas si el backend está momentáneamente caído.

### 6.2 Payload de telemetría (JSON)

```json
{
  "ts": 1718040000,
  "fw": "1.2.0",
  "device": { "air_temp": 24.5, "air_humidity": 61.2, "pressure": 1013.2 },
  "zones": [
    { "zone": "z1", "soil_moisture": 38.4, "valve": false },
    { "zone": "z2", "soil_moisture": 52.1, "valve": true }
  ]
}
```

Una placa puede reportar varias zonas en un solo mensaje. El backend valida este payload con un **schema Zod** al recibirlo (validación en el borde).

---

## 7. Backend

### 7.1 Estructura (mono-proceso, módulos por dominio)

```
backend/
├── mqtt/        worker: se suscribe a EMQX, valida e ingesta telemetría
├── api/         Fastify: REST para el frontend (devices, zones, históricos)
├── ws/          WebSocket: push de telemetría en vivo al dashboard
├── db/          acceso a Postgres/Timescale (repositorios)
├── domain/      lógica pura (validación Zod; reglas de riego en Fase 3)
└── shared/      tipos compartidos con el frontend (el contrato)
```

**Decisión: mono-proceso con módulos separados.** Más simple de desplegar y debuggear; a la escala objetivo (decenas/cientos de placas) sobra de rendimiento. Los límites entre módulos ya están dibujados, así que separar `mqtt/` en su propio contenedor más adelante es trivial si hiciera falta. La lógica de riego vive en `domain/` como funciones puras (entran lecturas, sale una decisión), testeables sin MQTT ni DB.

### 7.2 Flujo de un dato (de punta a punta)

```
ESP32 publica telemetry
   │
   ▼
EMQX ─→ mqtt/worker recibe
   │      ├─ 1. valida payload con Zod        (inválido → descarta + loguea, no crashea)
   │      ├─ 2. resuelve device/zonas         (device desconocido → rechaza)
   │      ├─ 3. inserta en readings (Timescale)
   │      └─ 4. emite evento interno
   ▼
ws/ broadcastea a los clientes suscriptos a ese device
   ▼
React actualiza el gráfico en vivo
```

### 7.3 Manejo de errores

- **Payload inválido:** se descarta y se loguea con contexto; nunca tira el worker.
- **DB caída:** reintentos con backoff; los mensajes quedan en el broker (QoS 1) hasta poder persistir.
- **MQTT desconectado:** reconexión automática con backoff; al reconectar re-suscribe.
- **Device no reconocido:** se rechaza la telemetría (no se auto-crean devices; eso pasa por el provisioning de Fase 2).

---

## 8. Frontend

### 8.1 Librerías

| Necesidad | Elección |
|---|---|
| Server state / fetching | TanStack Query |
| Tiempo real | WebSocket nativo + hook propio |
| Routing | React Router |
| Estado de UI | Zustand (mínimo) |
| Estilos | Tailwind |
| Componentes | shadcn/ui (sobre Radix) |
| Gráficos de series | uPlot |

### 8.2 Estructura (por feature)

```
frontend/src/
├── features/
│   ├── auth/         login, registro, guard de rutas        [auth real en Fase 2]
│   ├── dashboard/    vista en vivo (KPIs + gráficos + estado de zonas)
│   ├── devices/      gestión de placas y zonas
│   └── history/      exploración de históricos
├── lib/              cliente API, cliente WebSocket, tipos compartidos
└── components/       UI reutilizable (shadcn)
```

### 8.3 Tiempo real

Hook `useLiveTelemetry(deviceId)`:
1. Al montar, TanStack Query trae el histórico reciente (REST).
2. Abre el WebSocket y se suscribe a ese device.
3. Cada lectura nueva del WS se appendea al dataset → el gráfico (uPlot) avanza solo.
4. Al desmontar, cierra la suscripción.

### 8.4 Auth (preparación en Fase 1, completa en Fase 2)

Login con email+password → el backend emite un **JWT en cookie httpOnly** (no en localStorage: inmune a XSS, la vulnerabilidad de V1). React Router protege las rutas privadas. En Fase 1 el dashboard puede operar contra el usuario sembrado; el flujo completo de cuentas es Fase 2.

---

## 9. Alcance concreto de Fase 0 y Fase 1

### Fase 0 — Fundaciones de infra
- Monorepo (backend, frontend, firmware, infra).
- `docker-compose.yml`: EMQX + Postgres/TimescaleDB + backend + frontend.
- TLS para MQTT (certificados) y para el frontend.
- Migraciones de DB: tablas `users`, `devices`, `zones` y la hypertable `readings`.
- Seed de un usuario + un device + zonas para desarrollo.
- Tipos compartidos (`shared/`) con el contrato de telemetría.
- CI: lint + typecheck + tests en cada push.

### Fase 1 — Camino feliz de telemetría
- Firmware ESP32 (PlatformIO): lee sensores reales, arma el payload y publica por MQTT/TLS.
- Backend: worker MQTT que valida (Zod), persiste en `readings` y emite por WebSocket; endpoint REST de histórico reciente.
- Frontend: dashboard que muestra KPIs y un gráfico uPlot en vivo de un device.

**Fuera de alcance de Fase 0/1 (fases posteriores):** registro/login real y multi-tenancy (F2), provisioning/auth por dispositivo (F2), control manual y reglas (F3), recomendaciones y alertas (F4).

---

## 10. Estrategia de testing

- **Backend:**
  - Unit: lógica pura de `domain/` (validación, futuras reglas).
  - Integración: API + DB con Postgres/Timescale real vía testcontainers; worker MQTT contra un broker de test.
  - Objetivo de cobertura: 80%+.
- **Frontend:** component tests con Vitest + Testing Library; e2e del camino de dashboard con Playwright.
- **Firmware:** tests de lógica pura (armado de payload, parsing) con el entorno `native` de PlatformIO.
- **TDD:** test primero (RED → GREEN → refactor) según el flujo de trabajo del proyecto.

---

## 11. Pendientes / decisiones diferidas

- **Detalles finos de hardware** (modelos exactos de sensores, cableado, alimentación/solar, deep-sleep): sesión dedicada antes/durante el firmware.
- **Telemetría de batería y nivel de tanque:** se sumará como nuevas `metric` (cero migración gracias al formato narrow). Pendiente de definir junto al hardware.
- **Detalle de Fases 2–4:** se especifican al llegar a cada una.
```

