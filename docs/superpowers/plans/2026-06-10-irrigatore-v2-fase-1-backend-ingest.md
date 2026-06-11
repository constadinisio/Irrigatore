# Irrigatore V2 — Fase 1 Backend: Ingest de Telemetría — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Lograr el primer camino de telemetría funcionando de punta a punta: un device-simulator publica lecturas por MQTT → un worker las valida y persiste en TimescaleDB → un endpoint REST sirve el histórico reciente y un WebSocket emite las lecturas en vivo.

**Architecture:** Backend mono-proceso Node/TypeScript (Fastify) que arranca tres responsabilidades en módulos separados: un worker MQTT (`mqtt/`) que se suscribe a EMQX, valida con Zod, mapea el payload a filas *narrow* y persiste; una API REST (`api/`) que sirve históricos; y un servidor WebSocket (`ws/`) que reenvía cada lectura nueva en vivo. Un `EventEmitter` interno desacopla el worker de los consumidores (WS). El device real se sustituye por un device-simulator (`tools/device-simulator`) que publica el contrato acordado, de modo que todo el camino software se construye y testea sin hardware.

**Tech Stack:** Node + TypeScript, Fastify, @fastify/websocket, mqtt (mqtt.js), Drizzle ORM, postgres, Zod, Vitest, EMQX, PostgreSQL + TimescaleDB.

---

## Contexto del codebase existente (Fase 0, ya en `main`)

- Monorepo pnpm. **En esta máquina pnpm se invoca como `corepack pnpm`** (con `COREPACK_ENABLE_DOWNLOAD_PROMPT=0` adelante). La forma recursiva es `corepack pnpm -r test`; la filtrada `corepack pnpm --filter @irrigatore/backend test`.
- **Postgres/Timescale corre en Docker en el puerto host 5433** (5432 está ocupado por un Postgres local). El `.env` local ya tiene `DATABASE_URL=postgres://irrigatore:changeme_dev@localhost:5433/irrigatore`. EMQX corre con MQTT en `1883` (TCP) y `8883` (TLS).
- `@irrigatore/shared` exporta el contrato: `TelemetryPayloadSchema`, tipos `TelemetryPayload`/`ZoneReading`/`DeviceEnv`, y `METRICS`/`Metric` (`"soil_moisture" | "air_temp" | "air_humidity" | "pressure"`).
- `@irrigatore/backend` tiene `src/db/schema.ts` (tablas `users`, `devices`, `zones`, `readings`), `src/db/client.ts` (export `db` y `sql`), `src/db/migrate.ts` (runner), `src/db/seed.ts`. El seed deja `devices.device_key = 'dev-device-001'` con zonas `z1`, `z2`.
- Migraciones SQL en `db/migrations/` (`0000_*.sql` tablas, `0001_hypertable.sql`).

## Decisiones de implementación tomadas (validar al revisar)

1. **Identificador externo = `device_key`.** Los topics MQTT y las rutas REST/WS usan el `device_key` legible (ej. `dev-device-001`), no el UUID. El worker/handlers resuelven `device_key → devices.id` (UUID) internamente. Topic de telemetría: `irrigatore/{deviceKey}/telemetry`.
2. **`readings` historiza solo las 4 métricas numéricas** de `METRICS` (`soil_moisture` por zona; `air_temp`/`air_humidity`/`pressure` a nivel device con `zone_id = NULL`). El estado del relé (`valve`, booleano) NO se historiza en Fase 1; sí viaja en el broadcast WebSocket para el dashboard en vivo. (Historiar el valve se evalúa en una fase posterior.)
3. **En dev el worker y el simulador usan MQTT por TCP (`1883`).** El listener TLS (`8883`) se verifica con un smoke test (Task 3) pero el cert autofirmado de dev haría ruido en cada conexión; producción usará `mqtts://`. La URL es configurable por `MQTT_URL`.
4. **Zona desconocida → se descarta esa métrica y se loguea** (no se auto-crea; el provisioning es Fase 2). Device desconocido → se descarta el mensaje entero.

---

## File Structure

```
packages/backend/
├── package.json                         # + deps: fastify, @fastify/websocket, mqtt
├── src/
│   ├── index.ts                         # bootstrap mono-proceso (arranca worker + Fastify + WS)
│   ├── config.ts                        # lee/valida env (DATABASE_URL, MQTT_URL, PORT)
│   ├── bus.ts                           # EventEmitter tipado (evento 'telemetry')
│   ├── domain/
│   │   ├── readings.ts                  # mapTelemetryToReadings (lógica pura)
│   │   └── readings.test.ts
│   ├── db/
│   │   ├── migrate.ts                   # (MODIFICADO) con tabla de tracking schema_migrations
│   │   ├── migrate.test.ts              # (MODIFICADO) hermético
│   │   ├── seed.test.ts                 # (MODIFICADO) hermético
│   │   ├── schema.test.ts               # (sin cambios funcionales)
│   │   ├── test-helpers.ts              # (NUEVO) requireDb() para gatear integración
│   │   ├── readings-repo.ts             # (NUEVO) insertReadings, getRecentReadings, resolveDevice, zoneIdMap
│   │   └── readings-repo.test.ts
│   ├── mqtt/
│   │   ├── worker.ts                    # conecta EMQX, suscribe, valida, persiste, emite evento
│   │   └── worker.test.ts
│   ├── api/
│   │   ├── server.ts                    # crea la instancia Fastify + rutas
│   │   ├── readings-routes.ts           # GET /api/devices/:deviceKey/readings
│   │   └── readings-routes.test.ts
│   └── ws/
│       └── live.ts                      # plugin @fastify/websocket: /ws/:deviceKey
└── db/migrations/                       # (en la raíz del repo) sin cambios de esquema en Fase 1

tools/device-simulator/
├── package.json                         # @irrigatore/shared + mqtt
├── tsconfig.json
└── src/
    └── index.ts                         # publica TelemetryPayload a EMQX cada N segundos
```

---

## Task 1: Tracking de migraciones (saldar deuda MEDIUM-2)

**Files:**
- Modify: `packages/backend/src/db/migrate.ts`
- Test: `packages/backend/src/db/migrate-tracking.test.ts`

**Contexto:** el runner actual re-aplica TODOS los `.sql` en cada corrida. La primera migración no idempotente lo rompería. Agregamos una tabla `schema_migrations` que registra cada filename aplicado; el runner saltea los ya aplicados.

- [ ] **Step 1: Escribir el test que falla**

```ts
// packages/backend/src/db/migrate-tracking.test.ts
import { describe, it, expect, afterAll } from "vitest";
import postgres from "postgres";
import { requireDb } from "./test-helpers.js";

const url = process.env.DATABASE_URL;
const sql = postgres(url ?? "postgres://x", { max: 1 });
afterAll(async () => { await sql.end(); });

requireDb(describe)("tracking de migraciones", () => {
  it("registra cada migración aplicada en schema_migrations", async () => {
    const rows = await sql`SELECT filename FROM schema_migrations ORDER BY filename`;
    const names = rows.map((r) => r.filename as string);
    expect(names).toContain("0000_spotty_caretaker.sql");
    expect(names).toContain("0001_hypertable.sql");
  });
});
```

> Nota: el nombre `0000_spotty_caretaker.sql` es el generado en Fase 0. Si en tu repo el archivo `0000_*.sql` tiene otro sufijo, usá el nombre real (listá `db/migrations/`).

- [ ] **Step 2: Crear el helper `requireDb` (necesario para el test de arriba)**

```ts
// packages/backend/src/db/test-helpers.ts
import type { describe } from "vitest";

/**
 * Gatea suites de integración: si no hay DATABASE_URL, se skipean
 * en vez de fallar con conexión rechazada. Devuelve describe o describe.skip.
 */
export function requireDb(d: typeof describe) {
  return process.env.DATABASE_URL ? d : d.skip;
}
```

- [ ] **Step 3: Correr el test y verificar que FALLA**

Run: `set -a; . ./.env; set +a; COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/db/migrate-tracking.test.ts`
Expected: FAIL — la tabla `schema_migrations` no existe todavía.

- [ ] **Step 4: Reescribir el runner con tracking**

```ts
// packages/backend/src/db/migrate.ts
import { readdir, readFile } from "node:fs/promises";
import { join } from "node:path";
import postgres from "postgres";

const MIGRATIONS_DIR = join(process.cwd(), "../../db/migrations");

async function run() {
  const url = process.env.DATABASE_URL;
  if (!url) throw new Error("DATABASE_URL no está definida");
  const sql = postgres(url, { max: 1 });
  try {
    await sql`
      CREATE TABLE IF NOT EXISTS schema_migrations (
        filename text PRIMARY KEY,
        applied_at timestamptz NOT NULL DEFAULT now()
      )`;

    const applied = new Set(
      (await sql`SELECT filename FROM schema_migrations`).map((r) => r.filename as string),
    );

    const files = (await readdir(MIGRATIONS_DIR))
      .filter((f) => f.endsWith(".sql"))
      .sort();

    for (const file of files) {
      if (applied.has(file)) {
        console.log(`Saltando ${file} (ya aplicada).`);
        continue;
      }
      const contents = await readFile(join(MIGRATIONS_DIR, file), "utf8");
      console.log(`Aplicando ${file}...`);
      await sql.begin(async (tx) => {
        await tx.unsafe(contents);
        await tx`INSERT INTO schema_migrations (filename) VALUES (${file})`;
      });
    }
    console.log("Migraciones aplicadas.");
  } finally {
    await sql.end();
  }
}

run().catch((err) => {
  console.error(err);
  process.exit(1);
});
```

> Nota: las migraciones existentes (`0000`, `0001`) ya fueron aplicadas en la DB de dev pero AÚN no están registradas en `schema_migrations` (que recién se crea). Para que queden registradas sin re-ejecutarlas, ver Step 5.

- [ ] **Step 5: Registrar como aplicadas las migraciones ya existentes en la DB de dev**

Como las tablas ya existen en la DB de dev, hay que sembrar `schema_migrations` con los nombres ya aplicados para que el runner no intente re-ejecutarlos. Ejecutá una sola vez (ajustá el nombre del `0000_*` al real de tu repo):

```bash
set -a; . ./.env; set +a
docker compose exec -T postgres psql -U irrigatore -d irrigatore -p 5432 -c \
"CREATE TABLE IF NOT EXISTS schema_migrations (filename text PRIMARY KEY, applied_at timestamptz NOT NULL DEFAULT now()); \
 INSERT INTO schema_migrations (filename) VALUES ('0000_spotty_caretaker.sql'),('0001_hypertable.sql') ON CONFLICT DO NOTHING;"
```

> Nota: dentro del contenedor el puerto es 5432 (el 5433 es solo el mapeo del host).

- [ ] **Step 6: Correr el runner y el test, verificar PASS**

```bash
set -a; . ./.env; set +a
COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend db:migrate
COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/db/migrate-tracking.test.ts
```
Expected: el runner imprime "Saltando 0000... (ya aplicada)", "Saltando 0001... (ya aplicada)"; el test PASA.

- [ ] **Step 7: Commit**

```bash
git add packages/backend/src/db/migrate.ts packages/backend/src/db/migrate-tracking.test.ts packages/backend/src/db/test-helpers.ts
git commit -m "feat(backend): tracking de migraciones aplicadas en schema_migrations"
```

---

## Task 2: Hacer herméticos los tests de integración existentes (saldar deuda MEDIUM-3)

**Files:**
- Modify: `packages/backend/src/db/migrate.test.ts`
- Modify: `packages/backend/src/db/seed.test.ts`

**Contexto:** sin `DATABASE_URL`, estos tests fallan con conexión rechazada en vez de skipearse. Los gateamos con el helper `requireDb` de la Task 1.

- [ ] **Step 1: Modificar `migrate.test.ts` para usar `requireDb`**

```ts
// packages/backend/src/db/migrate.test.ts
import { describe, it, expect, afterAll } from "vitest";
import postgres from "postgres";
import { requireDb } from "./test-helpers.js";

const url = process.env.DATABASE_URL;
const sql = postgres(url ?? "postgres://x", { max: 1 });

afterAll(async () => { await sql.end(); });

requireDb(describe)("hypertable readings", () => {
  it("readings está registrada como hypertable de Timescale", async () => {
    const rows = await sql`
      SELECT hypertable_name FROM timescaledb_information.hypertables
      WHERE hypertable_name = 'readings'`;
    expect(rows.length).toBe(1);
  });
});
```

- [ ] **Step 2: Modificar `seed.test.ts` para usar `requireDb`**

```ts
// packages/backend/src/db/seed.test.ts
import { describe, it, expect, afterAll } from "vitest";
import postgres from "postgres";
import { requireDb } from "./test-helpers.js";

const url = process.env.DATABASE_URL;
const sql = postgres(url ?? "postgres://x", { max: 1 });
afterAll(async () => { await sql.end(); });

requireDb(describe)("seed", () => {
  it("crea un device de desarrollo con dos zonas", async () => {
    const devs = await sql`SELECT id, device_key FROM devices WHERE device_key = 'dev-device-001'`;
    expect(devs.length).toBe(1);
    const zns = await sql`SELECT name FROM zones WHERE device_id = ${devs[0].id} ORDER BY name`;
    expect(zns.map((z) => z.name)).toEqual(["z1", "z2"]);
  });
});
```

- [ ] **Step 3: Verificar que CON DATABASE_URL siguen pasando**

Run: `set -a; . ./.env; set +a; COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test`
Expected: PASS (todos los tests de backend verdes).

- [ ] **Step 4: Verificar que SIN DATABASE_URL se skipean (no fallan)**

Run (sin cargar `.env`, en un shell limpio): `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test`
Expected: los tests de integración aparecen como `skipped`; los unit (schema) pasan; NO hay fallos de conexión.

- [ ] **Step 5: Commit**

```bash
git add packages/backend/src/db/migrate.test.ts packages/backend/src/db/seed.test.ts
git commit -m "test(backend): gatear tests de integración con requireDb (herméticos)"
```

---

## Task 3: Smoke test del listener TLS de EMQX (saldar deuda MEDIUM-4)

**Files:**
- Create: `packages/backend/src/mqtt/tls-smoke.test.ts`
- Modify: `packages/backend/package.json` (agregar dep `mqtt`)

**Contexto:** el listener `8883` (mqtts) se cableó en Fase 0 pero nunca se verificó que sirva TLS. Este test conecta por `mqtts://`, publica y recibe un mensaje, confirmando el camino TLS extremo a extremo. Requiere que los certs de dev existan (`infra/emqx/certs/cert.pem` + `key.pem`, ver `infra/emqx/README.md`).

- [ ] **Step 1: Agregar la dependencia `mqtt` al backend**

Editá `packages/backend/package.json` y agregá en `dependencies` (manteniendo las existentes):
```json
    "mqtt": "^5.10.0",
```
Luego instalá: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm install`.

- [ ] **Step 2: Asegurar que los certs de dev existen**

```bash
test -f infra/emqx/certs/cert.pem || (mkdir -p infra/emqx/certs && cd infra/emqx/certs && openssl req -x509 -newkey rsa:2048 -nodes -keyout key.pem -out cert.pem -days 365 -subj "/CN=localhost" && cd -)
docker compose up -d emqx
```

- [ ] **Step 3: Escribir el smoke test**

```ts
// packages/backend/src/mqtt/tls-smoke.test.ts
import { describe, it, expect, afterAll } from "vitest";
import mqtt from "mqtt";

// Solo corre si se pide explícitamente (necesita EMQX con TLS levantado).
const runTls = process.env.RUN_TLS_SMOKE === "1";
const d = runTls ? describe : describe.skip;

let client: mqtt.MqttClient | undefined;
afterAll(async () => { await client?.endAsync(true); });

d("EMQX TLS (8883)", () => {
  it("conecta por mqtts, publica y recibe un mensaje", async () => {
    client = await mqtt.connectAsync("mqtts://localhost:8883", {
      rejectUnauthorized: false, // cert autofirmado de dev
      connectTimeout: 5000,
    });
    const topic = "irrigatore/_smoke/tls";
    await client.subscribeAsync(topic);
    const received = new Promise<string>((resolve) => {
      client!.on("message", (_t, payload) => resolve(payload.toString()));
    });
    await client.publishAsync(topic, "ok", { qos: 1 });
    await expect(received).resolves.toBe("ok");
  });
});
```

- [ ] **Step 4: Correr el smoke test (gateado por RUN_TLS_SMOKE)**

Run: `RUN_TLS_SMOKE=1 COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/mqtt/tls-smoke.test.ts`
Expected: PASS — confirma que 8883 sirve TLS y el round-trip publish/subscribe funciona.

> Si falla por "self signed certificate", revisá que `rejectUnauthorized: false` esté presente. Si falla por timeout, revisá que el listener SSL de EMQX esté arriba (`docker compose logs emqx | grep -i ssl`) y que los certs estén montados.

- [ ] **Step 5: Commit**

```bash
git add packages/backend/src/mqtt/tls-smoke.test.ts packages/backend/package.json pnpm-lock.yaml
git commit -m "test(backend): smoke test del listener TLS de EMQX (8883)"
```

---

## Task 4: Lógica pura de mapeo TelemetryPayload → filas readings

**Files:**
- Create: `packages/backend/src/domain/readings.ts`
- Test: `packages/backend/src/domain/readings.test.ts`

**Contexto:** transformación pura (sin DB ni MQTT), fácil de testear. Convierte un payload validado + el mapa de nombres de zona a UUID en las filas `narrow` a insertar.

- [ ] **Step 1: Escribir el test que falla**

```ts
// packages/backend/src/domain/readings.test.ts
import { describe, it, expect } from "vitest";
import { mapTelemetryToReadings, type ReadingRow } from "./readings.js";
import type { TelemetryPayload } from "@irrigatore/shared";

const payload: TelemetryPayload = {
  ts: 1718040000,
  fw: "1.2.0",
  device: { air_temp: 24.5, air_humidity: 61.2, pressure: 1013.2 },
  zones: [
    { zone: "z1", soil_moisture: 38.4, valve: false },
    { zone: "z2", soil_moisture: 52.1, valve: true },
  ],
};

const deviceId = "11111111-1111-1111-1111-111111111111";
const zoneIdByName = new Map([
  ["z1", "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"],
  ["z2", "bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb"],
]);

describe("mapTelemetryToReadings", () => {
  it("genera 3 métricas de device (zone_id null) + 1 soil por zona conocida", () => {
    const rows = mapTelemetryToReadings(payload, deviceId, zoneIdByName);
    const deviceRows = rows.filter((r) => r.zoneId === null);
    expect(deviceRows.map((r) => r.metric).sort()).toEqual(
      ["air_humidity", "air_temp", "pressure"],
    );
    const soil = rows.filter((r) => r.metric === "soil_moisture");
    expect(soil.length).toBe(2);
    expect(soil.every((r) => r.deviceId === deviceId)).toBe(true);
  });

  it("usa el timestamp del payload (segundos → Date)", () => {
    const rows = mapTelemetryToReadings(payload, deviceId, zoneIdByName);
    expect(rows[0].time.getTime()).toBe(1718040000 * 1000);
  });

  it("descarta la métrica soil de una zona desconocida", () => {
    const rows = mapTelemetryToReadings(payload, deviceId, new Map([["z1", "aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa"]]));
    const soil = rows.filter((r) => r.metric === "soil_moisture");
    expect(soil.length).toBe(1);
    expect(soil[0].zoneId).toBe("aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa");
  });
});
```

- [ ] **Step 2: Correr el test, verificar que FALLA**

Run: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/domain/readings.test.ts`
Expected: FAIL — no existe `./readings.js`.

- [ ] **Step 3: Implementar la lógica pura**

```ts
// packages/backend/src/domain/readings.ts
import type { TelemetryPayload, Metric } from "@irrigatore/shared";

export interface ReadingRow {
  time: Date;
  deviceId: string;
  zoneId: string | null;
  metric: Metric;
  value: number;
}

/**
 * Convierte un payload validado en filas narrow.
 * - Métricas ambientales del device → zoneId null.
 * - soil_moisture → una fila por zona cuyo nombre exista en zoneIdByName.
 *   Zonas desconocidas se descartan (no se inventan).
 */
export function mapTelemetryToReadings(
  payload: TelemetryPayload,
  deviceId: string,
  zoneIdByName: Map<string, string>,
): ReadingRow[] {
  const time = new Date(payload.ts * 1000);
  const rows: ReadingRow[] = [];

  const deviceMetrics: Metric[] = ["air_temp", "air_humidity", "pressure"];
  for (const metric of deviceMetrics) {
    rows.push({ time, deviceId, zoneId: null, metric, value: payload.device[metric] });
  }

  for (const z of payload.zones) {
    const zoneId = zoneIdByName.get(z.zone);
    if (!zoneId) continue; // zona desconocida → descartar
    rows.push({ time, deviceId, zoneId, metric: "soil_moisture", value: z.soil_moisture });
  }

  return rows;
}
```

- [ ] **Step 4: Correr el test, verificar que PASA**

Run: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/domain/readings.test.ts`
Expected: PASS — 3 tests verdes.

- [ ] **Step 5: Commit**

```bash
git add packages/backend/src/domain/readings.ts packages/backend/src/domain/readings.test.ts
git commit -m "feat(backend): mapeo puro de telemetría a filas readings (narrow)"
```

---

## Task 5: Repositorio de readings (resolución de device/zonas + persistencia + histórico)

**Files:**
- Create: `packages/backend/src/db/readings-repo.ts`
- Test: `packages/backend/src/db/readings-repo.test.ts`

**Contexto:** acceso a datos para el worker (resolver device_key → device, mapa de zonas, insertar filas) y para la API (histórico reciente). Usa el cliente `db`/`sql` de Drizzle existente.

- [ ] **Step 1: Escribir el test de integración que falla**

```ts
// packages/backend/src/db/readings-repo.test.ts
import { describe, it, expect, afterAll, beforeAll } from "vitest";
import postgres from "postgres";
import { requireDb } from "./test-helpers.js";
import { resolveDevice, getZoneIdMap, insertReadings, getRecentReadings } from "./readings-repo.js";
import type { ReadingRow } from "../domain/readings.js";

const url = process.env.DATABASE_URL;
const sql = postgres(url ?? "postgres://x", { max: 1 });
afterAll(async () => { await sql.end(); });

requireDb(describe)("readings-repo", () => {
  it("resolveDevice devuelve el id del device sembrado por device_key", async () => {
    const dev = await resolveDevice("dev-device-001");
    expect(dev?.id).toBeTruthy();
  });

  it("resolveDevice devuelve null para un device_key inexistente", async () => {
    expect(await resolveDevice("no-existe-999")).toBeNull();
  });

  it("getZoneIdMap mapea z1/z2 a uuids", async () => {
    const dev = await resolveDevice("dev-device-001");
    const map = await getZoneIdMap(dev!.id);
    expect(map.get("z1")).toBeTruthy();
    expect(map.get("z2")).toBeTruthy();
  });

  it("insertReadings persiste filas y getRecentReadings las devuelve", async () => {
    const dev = await resolveDevice("dev-device-001");
    const map = await getZoneIdMap(dev!.id);
    const t = new Date();
    const rows: ReadingRow[] = [
      { time: t, deviceId: dev!.id, zoneId: null, metric: "air_temp", value: 25.5 },
      { time: t, deviceId: dev!.id, zoneId: map.get("z1")!, metric: "soil_moisture", value: 40.1 },
    ];
    await insertReadings(rows);
    const recent = await getRecentReadings(dev!.id, { metric: "air_temp", limit: 5 });
    expect(recent.some((r) => Math.abs(r.value - 25.5) < 1e-6)).toBe(true);
  });
});
```

- [ ] **Step 2: Correr el test, verificar que FALLA**

Run: `set -a; . ./.env; set +a; COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/db/readings-repo.test.ts`
Expected: FAIL — no existe `./readings-repo.js`.

- [ ] **Step 3: Implementar el repositorio**

```ts
// packages/backend/src/db/readings-repo.ts
import { and, desc, eq } from "drizzle-orm";
import { db } from "./client.js";
import { devices, zones, readings } from "./schema.js";
import type { ReadingRow } from "../domain/readings.js";
import type { Metric } from "@irrigatore/shared";

export interface DeviceRef { id: string; deviceKey: string; }

export async function resolveDevice(deviceKey: string): Promise<DeviceRef | null> {
  const rows = await db
    .select({ id: devices.id, deviceKey: devices.deviceKey })
    .from(devices)
    .where(eq(devices.deviceKey, deviceKey))
    .limit(1);
  return rows[0] ?? null;
}

export async function getZoneIdMap(deviceId: string): Promise<Map<string, string>> {
  const rows = await db
    .select({ id: zones.id, name: zones.name })
    .from(zones)
    .where(eq(zones.deviceId, deviceId));
  return new Map(rows.map((z) => [z.name, z.id]));
}

export async function insertReadings(rows: ReadingRow[]): Promise<void> {
  if (rows.length === 0) return;
  await db.insert(readings).values(
    rows.map((r) => ({
      time: r.time,
      deviceId: r.deviceId,
      zoneId: r.zoneId,
      metric: r.metric,
      value: r.value,
    })),
  );
}

export interface RecentQuery { metric?: Metric; limit?: number; }

export async function getRecentReadings(
  deviceId: string,
  q: RecentQuery = {},
): Promise<Array<{ time: Date; metric: string; value: number; zoneId: string | null }>> {
  const limit = Math.min(q.limit ?? 100, 1000);
  const where = q.metric
    ? and(eq(readings.deviceId, deviceId), eq(readings.metric, q.metric))
    : eq(readings.deviceId, deviceId);
  const rows = await db
    .select({ time: readings.time, metric: readings.metric, value: readings.value, zoneId: readings.zoneId })
    .from(readings)
    .where(where)
    .orderBy(desc(readings.time))
    .limit(limit);
  return rows;
}
```

- [ ] **Step 4: Correr el test, verificar que PASA**

Run: `set -a; . ./.env; set +a; COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/db/readings-repo.test.ts`
Expected: PASS — 4 tests verdes.

- [ ] **Step 5: Commit**

```bash
git add packages/backend/src/db/readings-repo.ts packages/backend/src/db/readings-repo.test.ts
git commit -m "feat(backend): repositorio de readings (resolución device/zonas, persistencia, histórico)"
```

---

## Task 6: Config tipada + bus de eventos

**Files:**
- Create: `packages/backend/src/config.ts`
- Create: `packages/backend/src/bus.ts`
- Test: `packages/backend/src/config.test.ts`

**Contexto:** centralizar la lectura/validación de variables de entorno (con Zod) y un EventEmitter tipado que desacopla el worker de los consumidores (WebSocket).

- [ ] **Step 1: Escribir el test que falla**

```ts
// packages/backend/src/config.test.ts
import { describe, it, expect } from "vitest";
import { loadConfig } from "./config.js";

describe("loadConfig", () => {
  it("lee valores válidos del entorno provisto", () => {
    const cfg = loadConfig({
      DATABASE_URL: "postgres://u:p@localhost:5433/db",
      MQTT_URL: "mqtt://localhost:1883",
      PORT: "3000",
    });
    expect(cfg.databaseUrl).toContain("5433");
    expect(cfg.mqttUrl).toBe("mqtt://localhost:1883");
    expect(cfg.port).toBe(3000);
  });

  it("usa PORT por defecto 3000 si no está", () => {
    const cfg = loadConfig({
      DATABASE_URL: "postgres://u:p@localhost:5433/db",
      MQTT_URL: "mqtt://localhost:1883",
    });
    expect(cfg.port).toBe(3000);
  });

  it("lanza si falta DATABASE_URL", () => {
    expect(() => loadConfig({ MQTT_URL: "mqtt://localhost:1883" })).toThrow();
  });
});
```

- [ ] **Step 2: Correr el test, verificar que FALLA**

Run: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/config.test.ts`
Expected: FAIL — no existe `./config.js`.

- [ ] **Step 3: Implementar config y bus**

```ts
// packages/backend/src/config.ts
import { z } from "zod";

const EnvSchema = z.object({
  DATABASE_URL: z.string().min(1),
  MQTT_URL: z.string().min(1).default("mqtt://localhost:1883"),
  PORT: z.coerce.number().int().positive().default(3000),
});

export interface Config {
  databaseUrl: string;
  mqttUrl: string;
  port: number;
}

export function loadConfig(env: NodeJS.ProcessEnv = process.env): Config {
  const parsed = EnvSchema.parse(env);
  return { databaseUrl: parsed.DATABASE_URL, mqttUrl: parsed.MQTT_URL, port: parsed.PORT };
}
```

```ts
// packages/backend/src/bus.ts
import { EventEmitter } from "node:events";
import type { TelemetryPayload } from "@irrigatore/shared";

export interface TelemetryEvent {
  deviceKey: string;
  payload: TelemetryPayload;
}

class TypedBus extends EventEmitter {
  emitTelemetry(ev: TelemetryEvent) { this.emit("telemetry", ev); }
  onTelemetry(fn: (ev: TelemetryEvent) => void) { this.on("telemetry", fn); }
  offTelemetry(fn: (ev: TelemetryEvent) => void) { this.off("telemetry", fn); }
}

export const bus = new TypedBus();
```

- [ ] **Step 4: Correr el test, verificar que PASA**

Run: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/config.test.ts`
Expected: PASS — 3 tests verdes.

- [ ] **Step 5: Commit**

```bash
git add packages/backend/src/config.ts packages/backend/src/config.test.ts packages/backend/src/bus.ts
git commit -m "feat(backend): config tipada (Zod) y bus de eventos de telemetría"
```

---

## Task 7: Worker MQTT (suscribe, valida, persiste, emite evento)

**Files:**
- Create: `packages/backend/src/mqtt/worker.ts`
- Test: `packages/backend/src/mqtt/worker.test.ts`

**Contexto:** el corazón del ingest. Se suscribe a `irrigatore/+/telemetry`, extrae el `deviceKey` del topic, valida el payload con Zod, resuelve device/zonas, persiste con el repo y emite el evento en el bus. La lógica de procesamiento de un mensaje se aísla en `handleMessage` para testearla sin un broker real.

- [ ] **Step 1: Escribir el test que falla (procesamiento de un mensaje, sin broker)**

```ts
// packages/backend/src/mqtt/worker.test.ts
import { describe, it, expect, vi, afterEach } from "vitest";
import { handleMessage } from "./worker.js";
import * as repo from "../db/readings-repo.js";
import { bus } from "../bus.js";

const validPayload = JSON.stringify({
  ts: 1718040000,
  fw: "1.0.0",
  device: { air_temp: 24, air_humidity: 60, pressure: 1010 },
  zones: [{ zone: "z1", soil_moisture: 40, valve: false }],
});

afterEach(() => vi.restoreAllMocks());

describe("handleMessage", () => {
  it("device desconocido → no inserta nada", async () => {
    vi.spyOn(repo, "resolveDevice").mockResolvedValue(null);
    const insert = vi.spyOn(repo, "insertReadings").mockResolvedValue();
    await handleMessage("irrigatore/desconocido/telemetry", Buffer.from(validPayload));
    expect(insert).not.toHaveBeenCalled();
  });

  it("payload inválido → no inserta y no lanza", async () => {
    vi.spyOn(repo, "resolveDevice").mockResolvedValue({ id: "d1", deviceKey: "dev-device-001" });
    const insert = vi.spyOn(repo, "insertReadings").mockResolvedValue();
    await expect(
      handleMessage("irrigatore/dev-device-001/telemetry", Buffer.from("{ no json")),
    ).resolves.toBeUndefined();
    expect(insert).not.toHaveBeenCalled();
  });

  it("payload válido → inserta filas y emite evento en el bus", async () => {
    vi.spyOn(repo, "resolveDevice").mockResolvedValue({ id: "d1", deviceKey: "dev-device-001" });
    vi.spyOn(repo, "getZoneIdMap").mockResolvedValue(new Map([["z1", "z-uuid-1"]]));
    const insert = vi.spyOn(repo, "insertReadings").mockResolvedValue();
    const emitted = new Promise((resolve) => bus.onTelemetry(resolve));
    await handleMessage("irrigatore/dev-device-001/telemetry", Buffer.from(validPayload));
    expect(insert).toHaveBeenCalledOnce();
    const rows = insert.mock.calls[0][0];
    expect(rows.length).toBe(4); // 3 device + 1 zona z1
    await expect(emitted).resolves.toMatchObject({ deviceKey: "dev-device-001" });
  });
});
```

- [ ] **Step 2: Correr el test, verificar que FALLA**

Run: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/mqtt/worker.test.ts`
Expected: FAIL — no existe `./worker.js`.

- [ ] **Step 3: Implementar el worker**

```ts
// packages/backend/src/mqtt/worker.ts
import mqtt from "mqtt";
import { TelemetryPayloadSchema } from "@irrigatore/shared";
import { mapTelemetryToReadings } from "../domain/readings.js";
import { resolveDevice, getZoneIdMap, insertReadings } from "../db/readings-repo.js";
import { bus } from "../bus.js";

const TELEMETRY_RE = /^irrigatore\/([^/]+)\/telemetry$/;

/** Procesa un único mensaje de telemetría. Nunca lanza: loguea y descarta. */
export async function handleMessage(topic: string, raw: Buffer): Promise<void> {
  const m = TELEMETRY_RE.exec(topic);
  if (!m) return;
  const deviceKey = m[1];

  let json: unknown;
  try {
    json = JSON.parse(raw.toString());
  } catch {
    console.warn(`[mqtt] JSON inválido en ${topic}, descartado`);
    return;
  }

  const parsed = TelemetryPayloadSchema.safeParse(json);
  if (!parsed.success) {
    console.warn(`[mqtt] payload inválido en ${topic}: ${parsed.error.message}`);
    return;
  }

  try {
    const device = await resolveDevice(deviceKey);
    if (!device) {
      console.warn(`[mqtt] device desconocido '${deviceKey}', descartado`);
      return;
    }
    const zoneMap = await getZoneIdMap(device.id);
    const rows = mapTelemetryToReadings(parsed.data, device.id, zoneMap);
    await insertReadings(rows);
    bus.emitTelemetry({ deviceKey, payload: parsed.data });
  } catch (err) {
    console.error(`[mqtt] error persistiendo telemetría de '${deviceKey}':`, err);
  }
}

/** Arranca el worker: conecta a EMQX y se suscribe a la telemetría de todos los devices. */
export async function startMqttWorker(mqttUrl: string): Promise<mqtt.MqttClient> {
  const client = await mqtt.connectAsync(mqttUrl, {
    reconnectPeriod: 2000,
    rejectUnauthorized: false, // dev: cert autofirmado si se usa mqtts
  });
  await client.subscribeAsync("irrigatore/+/telemetry", { qos: 1 });
  client.on("message", (topic, payload) => { void handleMessage(topic, payload); });
  client.on("error", (err) => console.error("[mqtt] error de conexión:", err));
  console.log(`[mqtt] worker conectado a ${mqttUrl}, suscripto a irrigatore/+/telemetry`);
  return client;
}
```

- [ ] **Step 4: Correr el test, verificar que PASA**

Run: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/mqtt/worker.test.ts`
Expected: PASS — 3 tests verdes.

- [ ] **Step 5: Commit**

```bash
git add packages/backend/src/mqtt/worker.ts packages/backend/src/mqtt/worker.test.ts
git commit -m "feat(backend): worker MQTT de ingest (valida, persiste, emite evento)"
```

---

## Task 8: Device-simulator (publicador MQTT)

**Files:**
- Create: `tools/device-simulator/package.json`
- Create: `tools/device-simulator/tsconfig.json`
- Create: `tools/device-simulator/src/index.ts`

**Contexto:** sustituye al firmware real. Publica `TelemetryPayload` válido (usando el contrato de `@irrigatore/shared`) al topic del device sembrado cada N segundos, con valores que derivan suavemente (random walk) para ver movimiento en el dashboard.

- [ ] **Step 1: Crear `tools/device-simulator/package.json`**

```json
{
  "name": "@irrigatore/device-simulator",
  "version": "0.0.0",
  "private": true,
  "type": "module",
  "scripts": {
    "lint": "tsc --noEmit",
    "typecheck": "tsc --noEmit",
    "start": "tsx src/index.ts"
  },
  "dependencies": {
    "@irrigatore/shared": "workspace:*",
    "mqtt": "^5.10.0"
  },
  "devDependencies": {
    "@types/node": "^20.0.0",
    "tsx": "^4.16.0"
  }
}
```

- [ ] **Step 2: Crear `tools/device-simulator/tsconfig.json`**

```json
{
  "extends": "../../tsconfig.base.json",
  "compilerOptions": { "outDir": "dist", "rootDir": "src" },
  "include": ["src"]
}
```

- [ ] **Step 3: Implementar el simulador**

```ts
// tools/device-simulator/src/index.ts
import mqtt from "mqtt";
import { TelemetryPayloadSchema, type TelemetryPayload } from "@irrigatore/shared";

const MQTT_URL = process.env.MQTT_URL ?? "mqtt://localhost:1883";
const DEVICE_KEY = process.env.DEVICE_KEY ?? "dev-device-001";
const INTERVAL_MS = Number(process.env.INTERVAL_MS ?? 3000);

// Estado mutable local del simulador (random walk).
const state = {
  air_temp: 24, air_humidity: 60, pressure: 1013,
  z1: 40, z2: 50,
};

function drift(v: number, step: number, min: number, max: number): number {
  const next = v + (Math.sin(Date.now() / 5000 + v) * step);
  return Math.max(min, Math.min(max, Number(next.toFixed(2))));
}

function buildPayload(): TelemetryPayload {
  state.air_temp = drift(state.air_temp, 0.5, 5, 40);
  state.air_humidity = drift(state.air_humidity, 1, 20, 95);
  state.pressure = drift(state.pressure, 0.3, 990, 1030);
  state.z1 = drift(state.z1, 1.5, 0, 100);
  state.z2 = drift(state.z2, 1.5, 0, 100);
  const payload: TelemetryPayload = {
    ts: Math.floor(Date.now() / 1000),
    fw: "sim-1.0.0",
    device: { air_temp: state.air_temp, air_humidity: state.air_humidity, pressure: state.pressure },
    zones: [
      { zone: "z1", soil_moisture: state.z1, valve: state.z1 < 30 },
      { zone: "z2", soil_moisture: state.z2, valve: state.z2 < 30 },
    ],
  };
  // Auto-chequeo: el simulador nunca debe emitir un payload que el backend rechazaría.
  TelemetryPayloadSchema.parse(payload);
  return payload;
}

async function main() {
  const client = await mqtt.connectAsync(MQTT_URL, { rejectUnauthorized: false });
  const topic = `irrigatore/${DEVICE_KEY}/telemetry`;
  console.log(`[sim] conectado a ${MQTT_URL}, publicando en ${topic} cada ${INTERVAL_MS}ms`);
  setInterval(async () => {
    const payload = buildPayload();
    await client.publishAsync(topic, JSON.stringify(payload), { qos: 1 });
    console.log(`[sim] publicado ts=${payload.ts} z1=${payload.zones[0].soil_moisture} z2=${payload.zones[1].soil_moisture}`);
  }, INTERVAL_MS);
}

main().catch((err) => { console.error(err); process.exit(1); });
```

- [ ] **Step 4: Instalar deps y verificar typecheck**

```bash
COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm install
COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/device-simulator typecheck
```
Expected: instala sin error; typecheck sin errores.

- [ ] **Step 5: Smoke manual del simulador (opcional pero recomendado)**

Con EMQX arriba, corré el simulador unos segundos y confirmá que publica:
```bash
COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/device-simulator start
```
Expected: imprime "[sim] conectado..." y varias líneas "[sim] publicado ts=...". Cortá con Ctrl+C.

- [ ] **Step 6: Commit**

```bash
git add tools/device-simulator pnpm-lock.yaml
git commit -m "feat(tools): device-simulator que publica telemetría por MQTT"
```

---

## Task 9: API REST — histórico reciente

**Files:**
- Create: `packages/backend/src/api/readings-routes.ts`
- Create: `packages/backend/src/api/server.ts`
- Test: `packages/backend/src/api/readings-routes.test.ts`
- Modify: `packages/backend/package.json` (deps `fastify`, `@fastify/websocket`)

**Contexto:** endpoint REST que el dashboard usa para traer el histórico reciente. Devuelve 404 si el `device_key` no existe.

- [ ] **Step 1: Agregar deps al backend**

Editá `packages/backend/package.json`, agregá en `dependencies`:
```json
    "fastify": "^4.28.0",
    "@fastify/websocket": "^10.0.1",
```
Instalá: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm install`.

- [ ] **Step 2: Escribir el test de integración que falla**

```ts
// packages/backend/src/api/readings-routes.test.ts
import { describe, it, expect, afterAll, beforeAll } from "vitest";
import { requireDb } from "../db/test-helpers.js";
import { buildServer } from "./server.js";
import type { FastifyInstance } from "fastify";

let app: FastifyInstance;
beforeAll(async () => { app = await buildServer(); await app.ready(); });
afterAll(async () => { await app?.close(); });

requireDb(describe)("GET /api/devices/:deviceKey/readings", () => {
  it("404 si el device no existe", async () => {
    const res = await app.inject({ method: "GET", url: "/api/devices/no-existe/readings" });
    expect(res.statusCode).toBe(404);
  });

  it("200 con un array para el device sembrado", async () => {
    const res = await app.inject({ method: "GET", url: "/api/devices/dev-device-001/readings?metric=air_temp&limit=10" });
    expect(res.statusCode).toBe(200);
    expect(Array.isArray(res.json())).toBe(true);
  });
});
```

- [ ] **Step 3: Correr el test, verificar que FALLA**

Run: `set -a; . ./.env; set +a; COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/api/readings-routes.test.ts`
Expected: FAIL — no existe `./server.js`.

- [ ] **Step 4: Implementar rutas y server**

```ts
// packages/backend/src/api/readings-routes.ts
import type { FastifyInstance } from "fastify";
import { resolveDevice, getRecentReadings } from "../db/readings-repo.js";
import { METRICS, type Metric } from "@irrigatore/shared";

export async function readingsRoutes(app: FastifyInstance): Promise<void> {
  app.get<{
    Params: { deviceKey: string };
    Querystring: { metric?: string; limit?: string };
  }>("/api/devices/:deviceKey/readings", async (req, reply) => {
    const device = await resolveDevice(req.params.deviceKey);
    if (!device) return reply.code(404).send({ error: "device no encontrado" });

    const metric = req.query.metric as Metric | undefined;
    if (metric && !METRICS.includes(metric)) {
      return reply.code(400).send({ error: `metric inválida; válidas: ${METRICS.join(", ")}` });
    }
    const limit = req.query.limit ? Number(req.query.limit) : undefined;
    const rows = await getRecentReadings(device.id, { metric, limit });
    return reply.send(rows);
  });
}
```

```ts
// packages/backend/src/api/server.ts
import Fastify, { type FastifyInstance } from "fastify";
import { readingsRoutes } from "./readings-routes.js";

export async function buildServer(): Promise<FastifyInstance> {
  const app = Fastify({ logger: false });
  await app.register(readingsRoutes);
  return app;
}
```

- [ ] **Step 5: Correr el test, verificar que PASA**

Run: `set -a; . ./.env; set +a; COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/api/readings-routes.test.ts`
Expected: PASS — 2 tests verdes.

- [ ] **Step 6: Commit**

```bash
git add packages/backend/src/api packages/backend/package.json pnpm-lock.yaml
git commit -m "feat(backend): API REST de histórico de readings"
```

---

## Task 10: WebSocket en vivo

**Files:**
- Create: `packages/backend/src/ws/live.ts`
- Modify: `packages/backend/src/api/server.ts` (registrar el plugin WS)
- Test: `packages/backend/src/ws/live.test.ts`

**Contexto:** el dashboard se conecta a `/ws/:deviceKey` y recibe cada lectura nueva de ese device en cuanto el worker la emite en el bus. Usamos `@fastify/websocket`.

- [ ] **Step 1: Escribir el test que falla**

```ts
// packages/backend/src/ws/live.test.ts
import { describe, it, expect, afterAll, beforeAll } from "vitest";
import { buildServer } from "../api/server.js";
import { bus } from "../bus.js";
import type { FastifyInstance } from "fastify";
import type { TelemetryPayload } from "@irrigatore/shared";

let app: FastifyInstance;
let baseUrl: string;
beforeAll(async () => {
  app = await buildServer();
  await app.listen({ port: 0, host: "127.0.0.1" });
  const addr = app.server.address();
  const port = typeof addr === "object" && addr ? addr.port : 0;
  baseUrl = `ws://127.0.0.1:${port}`;
});
afterAll(async () => { await app?.close(); });

const payload: TelemetryPayload = {
  ts: 1718040000, fw: "1.0.0",
  device: { air_temp: 24, air_humidity: 60, pressure: 1010 },
  zones: [{ zone: "z1", soil_moisture: 40, valve: false }],
};

describe("WebSocket /ws/:deviceKey", () => {
  it("reenvía la telemetría emitida en el bus para ese device", async () => {
    const { WebSocket } = await import("ws");
    const ws = new WebSocket(`${baseUrl}/ws/dev-device-001`);
    const got = new Promise<string>((resolve) => ws.on("message", (d) => resolve(d.toString())));
    await new Promise<void>((resolve) => ws.on("open", () => resolve()));
    bus.emitTelemetry({ deviceKey: "dev-device-001", payload });
    const msg = JSON.parse(await got);
    expect(msg.deviceKey).toBe("dev-device-001");
    expect(msg.payload.zones[0].soil_moisture).toBe(40);
    ws.close();
  });
});
```

- [ ] **Step 2: Correr el test, verificar que FALLA**

Run: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/ws/live.test.ts`
Expected: FAIL — `/ws/:deviceKey` no está registrado (la conexión se rechaza).

- [ ] **Step 3: Implementar el plugin WS y registrarlo**

```ts
// packages/backend/src/ws/live.ts
import type { FastifyInstance } from "fastify";
import websocket from "@fastify/websocket";
import { bus, type TelemetryEvent } from "../bus.js";

export async function liveWs(app: FastifyInstance): Promise<void> {
  await app.register(websocket);
  app.register(async (scoped) => {
    scoped.get<{ Params: { deviceKey: string } }>(
      "/ws/:deviceKey",
      { websocket: true },
      (socket, req) => {
        const { deviceKey } = req.params;
        const listener = (ev: TelemetryEvent) => {
          if (ev.deviceKey !== deviceKey) return;
          socket.send(JSON.stringify(ev));
        };
        bus.onTelemetry(listener);
        socket.on("close", () => bus.offTelemetry(listener));
      },
    );
  });
}
```

Modificá `packages/backend/src/api/server.ts` para registrar el plugin WS:

```ts
// packages/backend/src/api/server.ts
import Fastify, { type FastifyInstance } from "fastify";
import { readingsRoutes } from "./readings-routes.js";
import { liveWs } from "../ws/live.js";

export async function buildServer(): Promise<FastifyInstance> {
  const app = Fastify({ logger: false });
  await app.register(liveWs);
  await app.register(readingsRoutes);
  return app;
}
```

- [ ] **Step 4: Asegurar la dep `ws` para el test**

`@fastify/websocket` trae `ws` como dependencia, pero el test lo importa directamente. Confirmá que resuelve; si el import de `ws` falla, agregalo a `devDependencies` del backend (`"ws": "^8.18.0"`, `"@types/ws": "^8.5.0"`) e instalá con `corepack pnpm install`.

- [ ] **Step 5: Correr el test, verificar que PASA**

Run: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/ws/live.test.ts`
Expected: PASS — el cliente WS recibe el evento.

- [ ] **Step 6: Commit**

```bash
git add packages/backend/src/ws packages/backend/src/api/server.ts packages/backend/package.json pnpm-lock.yaml
git commit -m "feat(backend): WebSocket de telemetría en vivo"
```

---

## Task 11: Bootstrap del backend (mono-proceso)

**Files:**
- Create: `packages/backend/src/index.ts`
- Modify: `packages/backend/package.json` (script `start` y `dev`)

**Contexto:** punto de entrada que integra todo: carga config, arranca el worker MQTT y levanta Fastify (REST + WS) en un solo proceso.

- [ ] **Step 1: Implementar el bootstrap**

```ts
// packages/backend/src/index.ts
import { loadConfig } from "./config.js";
import { startMqttWorker } from "./mqtt/worker.js";
import { buildServer } from "./api/server.js";

async function main() {
  const cfg = loadConfig();
  const app = await buildServer();
  const mqttClient = await startMqttWorker(cfg.mqttUrl);

  await app.listen({ port: cfg.port, host: "0.0.0.0" });
  console.log(`[backend] API + WS escuchando en :${cfg.port}`);

  const shutdown = async () => {
    console.log("[backend] cerrando...");
    await app.close();
    await mqttClient.endAsync();
    process.exit(0);
  };
  process.on("SIGINT", shutdown);
  process.on("SIGTERM", shutdown);
}

main().catch((err) => { console.error(err); process.exit(1); });
```

- [ ] **Step 2: Agregar scripts al backend**

Editá `packages/backend/package.json`, agregá en `scripts`:
```json
    "start": "tsx src/index.ts",
    "dev": "tsx watch src/index.ts",
```

- [ ] **Step 3: Verificar typecheck de todo el backend**

Run: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend typecheck`
Expected: sin errores.

- [ ] **Step 4: Commit**

```bash
git add packages/backend/src/index.ts packages/backend/package.json
git commit -m "feat(backend): bootstrap mono-proceso (worker MQTT + API + WS)"
```

---

## Task 12: Verificación end-to-end (simulador → worker → DB → REST/WS)

**Files:**
- Create: `packages/backend/src/e2e.test.ts` (gateado, manual)

**Contexto:** prueba el camino completo con todo levantado. Gateada por `RUN_E2E=1` porque depende de EMQX + DB corriendo.

- [ ] **Step 1: Escribir el test e2e**

```ts
// packages/backend/src/e2e.test.ts
import { describe, it, expect, afterAll, beforeAll } from "vitest";
import { buildServer } from "./api/server.js";
import { startMqttWorker } from "./mqtt/worker.js";
import { loadConfig } from "./config.js";
import mqtt from "mqtt";
import type { FastifyInstance } from "fastify";
import type { MqttClient } from "mqtt";

const run = process.env.RUN_E2E === "1";
const d = run ? describe : describe.skip;

let app: FastifyInstance;
let worker: MqttClient;
let pub: MqttClient;
beforeAll(async () => {
  const cfg = loadConfig();
  app = await buildServer();
  await app.ready();
  worker = await startMqttWorker(cfg.mqttUrl);
  pub = await mqtt.connectAsync(cfg.mqttUrl, { rejectUnauthorized: false });
});
afterAll(async () => {
  await pub?.endAsync();
  await worker?.endAsync();
  await app?.close();
});

d("e2e telemetría", () => {
  it("publicar telemetría queda disponible vía REST", async () => {
    const ts = Math.floor(Date.now() / 1000);
    const payload = {
      ts, fw: "e2e-1.0.0",
      device: { air_temp: 33.3, air_humidity: 55, pressure: 1001 },
      zones: [{ zone: "z1", soil_moisture: 12.3, valve: true }],
    };
    await pub.publishAsync("irrigatore/dev-device-001/telemetry", JSON.stringify(payload), { qos: 1 });
    // dar tiempo al worker a persistir
    await new Promise((r) => setTimeout(r, 800));
    const res = await app.inject({ method: "GET", url: "/api/devices/dev-device-001/readings?metric=air_temp&limit=5" });
    expect(res.statusCode).toBe(200);
    const rows = res.json() as Array<{ value: number }>;
    expect(rows.some((r) => Math.abs(r.value - 33.3) < 1e-6)).toBe(true);
  });
});
```

- [ ] **Step 2: Correr el e2e con todo levantado**

```bash
docker compose up -d postgres emqx
set -a; . ./.env; set +a
RUN_E2E=1 COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend test src/e2e.test.ts
```
Expected: PASS — la lectura publicada aparece en el histórico REST.

- [ ] **Step 3: Verificación manual del camino en vivo (opcional)**

En una terminal: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend start` (con `.env` cargado).
En otra: `COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/device-simulator start`.
Conectá un cliente WS a `ws://localhost:3000/ws/dev-device-001` (ej. `wscat -c ...`) y confirmá que llegan mensajes cada ~3s. Cortá ambos con Ctrl+C.

- [ ] **Step 4: Commit**

```bash
git add packages/backend/src/e2e.test.ts
git commit -m "test(backend): verificación e2e del camino de telemetría"
```

---

## Task 13: Actualizar el CI para Fase 1

**Files:**
- Modify: `.github/workflows/ci.yml`

**Contexto:** el CI ya corre migrate + seed + test. Hay que asegurar que (a) los nuevos tests de integración tengan `DATABASE_URL` (ya lo tienen) y (b) los tests gateados por `RUN_TLS_SMOKE`/`RUN_E2E` NO corran en CI (quedan skipeados, que es lo correcto — el CI no tiene EMQX). No hace falta levantar EMQX en CI para esta fase.

- [ ] **Step 1: Verificar el workflow actual**

Leé `.github/workflows/ci.yml`. El paso `pnpm test` corre todos los tests; los gateados por `RUN_TLS_SMOKE`/`RUN_E2E` se skipean solos (las env no están). Los de integración con DB corren porque `DATABASE_URL` está en el job. No se requieren cambios de servicios.

- [ ] **Step 2: Añadir un paso de typecheck del simulador (nuevo paquete)**

El CI corre `pnpm typecheck` que es recursivo (`pnpm -r typecheck`); el nuevo paquete `@irrigatore/device-simulator` ya define `typecheck`, así que queda cubierto automáticamente. Confirmá que el script raíz `typecheck` es `pnpm -r typecheck` (ya lo es desde Fase 0). No se requieren cambios.

- [ ] **Step 3: Verificación local del pipeline completo (simular el CI)**

```bash
set -a; . ./.env; set +a
COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm install --frozen-lockfile
COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm -r typecheck
COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend db:migrate
COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm --filter @irrigatore/backend db:seed
COREPACK_ENABLE_DOWNLOAD_PROMPT=0 corepack pnpm -r test
```
Expected: todo verde; los tests TLS/e2e aparecen skipeados.

- [ ] **Step 4: Commit (si hubo algún ajuste) y push**

```bash
git add .github/workflows/ci.yml
git commit -m "ci: cubrir Fase 1 (typecheck del simulador, tests de integración)" --allow-empty
git push -u origin feat/fase1-backend-ingest
```

- [ ] **Step 5: Verificar el CI en verde**

Run: `gh run watch`
Expected: el job `build` termina en verde.

---

## Definition of Done (Fase 1 Backend)

- [ ] Deudas MEDIUM de Fase 0 saldadas: tracking de migraciones, tests herméticos, smoke TLS de EMQX.
- [ ] El device-simulator publica `TelemetryPayload` válido por MQTT.
- [ ] El worker MQTT valida, mapea y persiste en `readings` (narrow); descarta payloads/devices/zonas inválidos sin crashear.
- [ ] El endpoint `GET /api/devices/:deviceKey/readings` sirve el histórico (404 si el device no existe).
- [ ] El WebSocket `/ws/:deviceKey` reenvía cada lectura nueva en vivo.
- [ ] El bootstrap mono-proceso levanta worker + REST + WS juntos.
- [ ] Test e2e (gateado) demuestra el camino completo simulador → worker → DB → REST.
- [ ] CI en verde.
```

