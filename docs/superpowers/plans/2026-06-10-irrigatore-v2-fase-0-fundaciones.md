# Irrigatore V2 — Fase 0: Fundaciones de Infra — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Dejar el monorepo, la infraestructura Docker (EMQX + Postgres/TimescaleDB), el esquema de base de datos, el contrato de telemetría compartido y el CI funcionando, de modo que las fases siguientes solo agreguen lógica.

**Architecture:** Monorepo con pnpm workspaces y tres paquetes (`shared`, `backend`, `frontend`) más herramientas en `tools/`. Toda la infra corre como contenedores Docker orquestados por Docker Compose. El esquema relacional y la hypertable de series temporales se versionan con migraciones (Drizzle ORM para lo relacional + SQL crudo para la hypertable de Timescale). El contrato MQTT vive en `shared/` como schemas Zod, fuente única de verdad de tipos para backend y frontend.

**Tech Stack:** pnpm workspaces, TypeScript, Vitest, Zod, Drizzle ORM, PostgreSQL + TimescaleDB, EMQX, Docker Compose, GitHub Actions.

---

## File Structure

```
/
├── package.json                      # raíz del workspace (scripts, devDeps comunes)
├── pnpm-workspace.yaml               # define los paquetes del workspace
├── tsconfig.base.json                # config TS compartida
├── docker-compose.yml                # EMQX + Postgres/Timescale (dev)
├── .env.example                      # variables de entorno de ejemplo
├── .github/workflows/ci.yml          # lint + typecheck + test
├── infra/
│   └── emqx/                         # config y certs TLS de EMQX
├── packages/
│   ├── shared/
│   │   ├── package.json
│   │   ├── tsconfig.json
│   │   └── src/
│   │       ├── index.ts              # re-exports públicos
│   │       └── telemetry.ts          # schema Zod + tipos del contrato MQTT
│   └── backend/
│       ├── package.json
│       ├── tsconfig.json
│       ├── drizzle.config.ts
│       └── src/
│           └── db/
│               ├── schema.ts         # tablas Drizzle (users, devices, zones, readings)
│               ├── client.ts         # conexión a Postgres
│               ├── migrate.ts        # runner de migraciones
│               └── seed.ts           # datos de desarrollo
└── db/
    └── migrations/
        └── 0001_hypertable.sql       # create_hypertable para readings (SQL crudo)
```

> **Nota:** `packages/frontend` y `tools/device-simulator` se crean en los planes de Fase 1; este plan deja las fundaciones.

---

## Task 1: Inicializar el monorepo con pnpm workspaces

**Files:**
- Create: `package.json`
- Create: `pnpm-workspace.yaml`
- Create: `tsconfig.base.json`
- Create: `.gitignore`

- [ ] **Step 1: Crear el `package.json` raíz**

```json
{
  "name": "irrigatore",
  "private": true,
  "type": "module",
  "scripts": {
    "lint": "pnpm -r lint",
    "typecheck": "pnpm -r typecheck",
    "test": "pnpm -r test"
  },
  "devDependencies": {
    "typescript": "^5.5.0",
    "vitest": "^2.0.0"
  },
  "packageManager": "pnpm@9.7.0"
}
```

- [ ] **Step 2: Crear `pnpm-workspace.yaml`**

```yaml
packages:
  - "packages/*"
  - "tools/*"
```

- [ ] **Step 3: Crear `tsconfig.base.json`**

```json
{
  "compilerOptions": {
    "target": "ES2022",
    "module": "ESNext",
    "moduleResolution": "Bundler",
    "strict": true,
    "esModuleInterop": true,
    "skipLibCheck": true,
    "declaration": true,
    "composite": true
  }
}
```

- [ ] **Step 4: Crear `.gitignore`**

```
node_modules/
dist/
.env
*.log
```

- [ ] **Step 5: Instalar y verificar**

Run: `pnpm install`
Expected: instala sin error y crea `pnpm-lock.yaml`.

- [ ] **Step 6: Commit**

```bash
git add package.json pnpm-workspace.yaml tsconfig.base.json .gitignore pnpm-lock.yaml
git commit -m "chore: inicializar monorepo con pnpm workspaces"
```

---

## Task 2: Paquete `shared` con el contrato de telemetría

**Files:**
- Create: `packages/shared/package.json`
- Create: `packages/shared/tsconfig.json`
- Create: `packages/shared/src/telemetry.ts`
- Create: `packages/shared/src/index.ts`
- Test: `packages/shared/src/telemetry.test.ts`

- [ ] **Step 1: Crear `packages/shared/package.json`**

```json
{
  "name": "@irrigatore/shared",
  "version": "0.0.0",
  "type": "module",
  "main": "./src/index.ts",
  "types": "./src/index.ts",
  "scripts": {
    "lint": "tsc --noEmit",
    "typecheck": "tsc --noEmit",
    "test": "vitest run"
  },
  "dependencies": {
    "zod": "^3.23.0"
  }
}
```

- [ ] **Step 2: Crear `packages/shared/tsconfig.json`**

```json
{
  "extends": "../../tsconfig.base.json",
  "compilerOptions": { "outDir": "dist", "rootDir": "src" },
  "include": ["src"]
}
```

- [ ] **Step 3: Escribir el test que falla**

```ts
// packages/shared/src/telemetry.test.ts
import { describe, it, expect } from "vitest";
import { TelemetryPayloadSchema } from "./telemetry.js";

const valid = {
  ts: 1718040000,
  fw: "1.2.0",
  device: { air_temp: 24.5, air_humidity: 61.2, pressure: 1013.2 },
  zones: [{ zone: "z1", soil_moisture: 38.4, valve: false }],
};

describe("TelemetryPayloadSchema", () => {
  it("acepta un payload válido", () => {
    const result = TelemetryPayloadSchema.safeParse(valid);
    expect(result.success).toBe(true);
  });

  it("rechaza un payload sin zonas", () => {
    const result = TelemetryPayloadSchema.safeParse({ ...valid, zones: [] });
    expect(result.success).toBe(false);
  });

  it("rechaza soil_moisture no numérico", () => {
    const bad = { ...valid, zones: [{ zone: "z1", soil_moisture: "x", valve: false }] };
    const result = TelemetryPayloadSchema.safeParse(bad);
    expect(result.success).toBe(false);
  });
});
```

- [ ] **Step 4: Correr el test para verificar que falla**

Run: `pnpm --filter @irrigatore/shared test`
Expected: FAIL — no existe `./telemetry.js`.

- [ ] **Step 5: Implementar el contrato**

```ts
// packages/shared/src/telemetry.ts
import { z } from "zod";

export const ZoneReadingSchema = z.object({
  zone: z.string().min(1),
  soil_moisture: z.number(),
  valve: z.boolean(),
});

export const DeviceEnvSchema = z.object({
  air_temp: z.number(),
  air_humidity: z.number(),
  pressure: z.number(),
});

export const TelemetryPayloadSchema = z.object({
  ts: z.number().int().positive(),
  fw: z.string().min(1),
  device: DeviceEnvSchema,
  zones: z.array(ZoneReadingSchema).min(1),
});

export type ZoneReading = z.infer<typeof ZoneReadingSchema>;
export type DeviceEnv = z.infer<typeof DeviceEnvSchema>;
export type TelemetryPayload = z.infer<typeof TelemetryPayloadSchema>;

export const METRICS = ["soil_moisture", "air_temp", "air_humidity", "pressure"] as const;
export type Metric = (typeof METRICS)[number];
```

- [ ] **Step 6: Crear el barrel `index.ts`**

```ts
// packages/shared/src/index.ts
export * from "./telemetry.js";
```

- [ ] **Step 7: Correr el test para verificar que pasa**

Run: `pnpm --filter @irrigatore/shared test`
Expected: PASS — los tres tests verdes.

- [ ] **Step 8: Commit**

```bash
git add packages/shared
git commit -m "feat(shared): contrato de telemetría con schema Zod"
```

---

## Task 3: Docker Compose con Postgres + TimescaleDB

**Files:**
- Create: `docker-compose.yml`
- Create: `.env.example`

- [ ] **Step 1: Crear `.env.example`**

```
POSTGRES_USER=irrigatore
POSTGRES_PASSWORD=changeme_dev
POSTGRES_DB=irrigatore
POSTGRES_PORT=5432
DATABASE_URL=postgres://irrigatore:changeme_dev@localhost:5432/irrigatore
```

- [ ] **Step 2: Crear `docker-compose.yml` (solo el servicio de DB por ahora)**

```yaml
services:
  postgres:
    image: timescale/timescaledb:2.16.1-pg16
    environment:
      POSTGRES_USER: ${POSTGRES_USER}
      POSTGRES_PASSWORD: ${POSTGRES_PASSWORD}
      POSTGRES_DB: ${POSTGRES_DB}
    ports:
      - "${POSTGRES_PORT}:5432"
    volumes:
      - pgdata:/var/lib/postgresql/data
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U ${POSTGRES_USER} -d ${POSTGRES_DB}"]
      interval: 5s
      timeout: 5s
      retries: 5

volumes:
  pgdata:
```

- [ ] **Step 3: Levantar y verificar la extensión TimescaleDB**

```bash
cp .env.example .env
docker compose up -d postgres
# esperar a que el healthcheck quede healthy
docker compose exec postgres psql -U irrigatore -d irrigatore -c "CREATE EXTENSION IF NOT EXISTS timescaledb; SELECT extname FROM pg_extension WHERE extname='timescaledb';"
```

Expected: la salida lista `timescaledb`.

- [ ] **Step 4: Commit**

```bash
git add docker-compose.yml .env.example
git commit -m "chore: servicio Postgres+TimescaleDB en Docker Compose"
```

---

## Task 4: Esquema relacional con Drizzle (users, devices, zones, readings)

**Files:**
- Create: `packages/backend/package.json`
- Create: `packages/backend/tsconfig.json`
- Create: `packages/backend/drizzle.config.ts`
- Create: `packages/backend/src/db/schema.ts`
- Create: `packages/backend/src/db/client.ts`
- Test: `packages/backend/src/db/schema.test.ts`

- [ ] **Step 1: Crear `packages/backend/package.json`**

```json
{
  "name": "@irrigatore/backend",
  "version": "0.0.0",
  "type": "module",
  "scripts": {
    "lint": "tsc --noEmit",
    "typecheck": "tsc --noEmit",
    "test": "vitest run",
    "db:generate": "drizzle-kit generate",
    "db:migrate": "tsx src/db/migrate.ts",
    "db:seed": "tsx src/db/seed.ts"
  },
  "dependencies": {
    "@irrigatore/shared": "workspace:*",
    "drizzle-orm": "^0.33.0",
    "postgres": "^3.4.4"
  },
  "devDependencies": {
    "drizzle-kit": "^0.24.0",
    "tsx": "^4.16.0"
  }
}
```

- [ ] **Step 2: Crear `packages/backend/tsconfig.json`**

```json
{
  "extends": "../../tsconfig.base.json",
  "compilerOptions": { "outDir": "dist", "rootDir": "src" },
  "include": ["src", "drizzle.config.ts"]
}
```

- [ ] **Step 3: Crear `drizzle.config.ts`**

```ts
import { defineConfig } from "drizzle-kit";

export default defineConfig({
  schema: "./src/db/schema.ts",
  out: "../../db/migrations",
  dialect: "postgresql",
  dbCredentials: { url: process.env.DATABASE_URL! },
});
```

- [ ] **Step 4: Escribir el test que falla (forma del esquema)**

```ts
// packages/backend/src/db/schema.test.ts
import { describe, it, expect } from "vitest";
import { users, devices, zones, readings } from "./schema.js";
import { getTableName } from "drizzle-orm";

describe("schema", () => {
  it("define las cuatro tablas con sus nombres", () => {
    expect(getTableName(users)).toBe("users");
    expect(getTableName(devices)).toBe("devices");
    expect(getTableName(zones)).toBe("zones");
    expect(getTableName(readings)).toBe("readings");
  });

  it("readings expone columnas metric y value (formato narrow)", () => {
    expect(readings.metric).toBeDefined();
    expect(readings.value).toBeDefined();
    expect(readings.zoneId).toBeDefined();
  });
});
```

- [ ] **Step 5: Correr el test para verificar que falla**

Run: `pnpm --filter @irrigatore/backend test`
Expected: FAIL — no existe `./schema.js`.

- [ ] **Step 6: Implementar el esquema**

```ts
// packages/backend/src/db/schema.ts
import {
  pgTable, uuid, text, timestamp, doublePrecision,
} from "drizzle-orm/pg-core";

export const users = pgTable("users", {
  id: uuid("id").primaryKey().defaultRandom(),
  email: text("email").notNull().unique(),
  passwordHash: text("password_hash").notNull(),
  createdAt: timestamp("created_at", { withTimezone: true }).notNull().defaultNow(),
});

export const devices = pgTable("devices", {
  id: uuid("id").primaryKey().defaultRandom(),
  userId: uuid("user_id").notNull().references(() => users.id),
  name: text("name").notNull(),
  deviceKey: text("device_key").notNull().unique(),
  status: text("status").notNull().default("offline"),
  fwVersion: text("fw_version"),
  lastSeenAt: timestamp("last_seen_at", { withTimezone: true }),
});

export const zones = pgTable("zones", {
  id: uuid("id").primaryKey().defaultRandom(),
  deviceId: uuid("device_id").notNull().references(() => devices.id),
  name: text("name").notNull(),
  cropType: text("crop_type"),
  createdAt: timestamp("created_at", { withTimezone: true }).notNull().defaultNow(),
});

// Formato narrow: una fila por métrica. zoneId NULL = métrica ambiental del device.
export const readings = pgTable("readings", {
  time: timestamp("time", { withTimezone: true }).notNull(),
  deviceId: uuid("device_id").notNull().references(() => devices.id),
  zoneId: uuid("zone_id").references(() => zones.id),
  metric: text("metric").notNull(),
  value: doublePrecision("value").notNull(),
});
```

- [ ] **Step 7: Crear el cliente de conexión**

```ts
// packages/backend/src/db/client.ts
import { drizzle } from "drizzle-orm/postgres-js";
import postgres from "postgres";
import * as schema from "./schema.js";

const url = process.env.DATABASE_URL;
if (!url) throw new Error("DATABASE_URL no está definida");

export const sql = postgres(url);
export const db = drizzle(sql, { schema });
```

- [ ] **Step 8: Correr el test para verificar que pasa**

Run: `pnpm --filter @irrigatore/backend test`
Expected: PASS — ambos tests verdes.

- [ ] **Step 9: Generar la migración relacional**

Run: `pnpm --filter @irrigatore/backend db:generate`
Expected: crea `db/migrations/0000_*.sql` con las tablas.

- [ ] **Step 10: Commit**

```bash
git add packages/backend db/migrations
git commit -m "feat(backend): esquema relacional con Drizzle (users, devices, zones, readings)"
```

---

## Task 5: Convertir `readings` en hypertable de Timescale

**Files:**
- Create: `db/migrations/0001_hypertable.sql`
- Create: `packages/backend/src/db/migrate.ts`
- Test: `packages/backend/src/db/migrate.test.ts`

- [ ] **Step 1: Escribir la migración SQL cruda**

```sql
-- db/migrations/0001_hypertable.sql
CREATE EXTENSION IF NOT EXISTS timescaledb;
SELECT create_hypertable('readings', 'time', if_not_exists => TRUE, migrate_data => TRUE);
CREATE INDEX IF NOT EXISTS readings_device_time_idx ON readings (device_id, time DESC);
CREATE INDEX IF NOT EXISTS readings_zone_time_idx ON readings (zone_id, time DESC);
```

- [ ] **Step 2: Escribir el runner de migraciones**

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
    const files = (await readdir(MIGRATIONS_DIR))
      .filter((f) => f.endsWith(".sql"))
      .sort();
    for (const file of files) {
      const contents = await readFile(join(MIGRATIONS_DIR, file), "utf8");
      console.log(`Aplicando ${file}...`);
      await sql.unsafe(contents);
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

- [ ] **Step 3: Escribir el test de integración que falla**

```ts
// packages/backend/src/db/migrate.test.ts
import { describe, it, expect, beforeAll, afterAll } from "vitest";
import postgres from "postgres";

// Requiere DATABASE_URL apuntando a una DB de test (docker compose up postgres).
const url = process.env.DATABASE_URL!;
const sql = postgres(url, { max: 1 });

afterAll(async () => { await sql.end(); });

describe("hypertable readings", () => {
  it("readings está registrada como hypertable de Timescale", async () => {
    const rows = await sql`
      SELECT hypertable_name FROM timescaledb_information.hypertables
      WHERE hypertable_name = 'readings'`;
    expect(rows.length).toBe(1);
  });
});
```

- [ ] **Step 4: Correr el test para verificar que falla**

```bash
docker compose up -d postgres   # si no está levantado
pnpm --filter @irrigatore/backend test src/db/migrate.test.ts
```
Expected: FAIL — `readings` aún no es hypertable (migraciones no aplicadas).

- [ ] **Step 5: Aplicar las migraciones**

Run: `pnpm --filter @irrigatore/backend db:migrate`
Expected: imprime "Aplicando 0000_*.sql", "Aplicando 0001_hypertable.sql", "Migraciones aplicadas."

- [ ] **Step 6: Correr el test para verificar que pasa**

Run: `pnpm --filter @irrigatore/backend test src/db/migrate.test.ts`
Expected: PASS — `readings` es hypertable.

- [ ] **Step 7: Commit**

```bash
git add db/migrations/0001_hypertable.sql packages/backend/src/db/migrate.ts packages/backend/src/db/migrate.test.ts
git commit -m "feat(backend): convertir readings en hypertable de Timescale + runner de migraciones"
```

---

## Task 6: Seed de datos de desarrollo

**Files:**
- Create: `packages/backend/src/db/seed.ts`
- Test: `packages/backend/src/db/seed.test.ts`

- [ ] **Step 1: Escribir el test de integración que falla**

```ts
// packages/backend/src/db/seed.test.ts
import { describe, it, expect, afterAll } from "vitest";
import postgres from "postgres";

const url = process.env.DATABASE_URL!;
const sql = postgres(url, { max: 1 });
afterAll(async () => { await sql.end(); });

describe("seed", () => {
  it("crea un device de desarrollo con dos zonas", async () => {
    const devs = await sql`SELECT id, device_key FROM devices WHERE device_key = 'dev-device-001'`;
    expect(devs.length).toBe(1);
    const zns = await sql`SELECT name FROM zones WHERE device_id = ${devs[0].id} ORDER BY name`;
    expect(zns.map((z) => z.name)).toEqual(["z1", "z2"]);
  });
});
```

- [ ] **Step 2: Correr el test para verificar que falla**

Run: `pnpm --filter @irrigatore/backend test src/db/seed.test.ts`
Expected: FAIL — no hay device sembrado.

- [ ] **Step 3: Implementar el seed (idempotente)**

```ts
// packages/backend/src/db/seed.ts
import postgres from "postgres";

async function run() {
  const url = process.env.DATABASE_URL;
  if (!url) throw new Error("DATABASE_URL no está definida");
  const sql = postgres(url, { max: 1 });
  try {
    const [user] = await sql`
      INSERT INTO users (email, password_hash)
      VALUES ('dev@irrigatore.local', 'x')
      ON CONFLICT (email) DO UPDATE SET email = EXCLUDED.email
      RETURNING id`;

    const [device] = await sql`
      INSERT INTO devices (user_id, name, device_key, status)
      VALUES (${user.id}, 'Placa de desarrollo', 'dev-device-001', 'offline')
      ON CONFLICT (device_key) DO UPDATE SET name = EXCLUDED.name
      RETURNING id`;

    for (const name of ["z1", "z2"]) {
      await sql`
        INSERT INTO zones (device_id, name, crop_type)
        VALUES (${device.id}, ${name}, 'tomate')
        ON CONFLICT DO NOTHING`;
    }
    console.log("Seed aplicado.");
  } finally {
    await sql.end();
  }
}

run().catch((err) => { console.error(err); process.exit(1); });
```

> **Nota:** el `ON CONFLICT DO NOTHING` de zonas asume que no se re-siembran duplicados; en una DB ya sembrada el test sigue pasando porque las filas existen. Para una DB limpia, corré el seed una vez antes del test.

- [ ] **Step 4: Aplicar el seed**

Run: `pnpm --filter @irrigatore/backend db:seed`
Expected: imprime "Seed aplicado."

- [ ] **Step 5: Correr el test para verificar que pasa**

Run: `pnpm --filter @irrigatore/backend test src/db/seed.test.ts`
Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add packages/backend/src/db/seed.ts packages/backend/src/db/seed.test.ts
git commit -m "feat(backend): seed de datos de desarrollo (usuario + device + zonas)"
```

---

## Task 7: Sumar EMQX al Docker Compose con TLS

**Files:**
- Modify: `docker-compose.yml`
- Create: `infra/emqx/README.md`
- Modify: `.env.example`

- [ ] **Step 1: Agregar variables MQTT a `.env.example`**

Agregar al final de `.env.example`:

```
MQTT_URL=mqtts://localhost:8883
MQTT_DASHBOARD_PORT=18083
```

- [ ] **Step 2: Agregar el servicio EMQX a `docker-compose.yml`**

Agregar dentro de `services:`:

```yaml
  emqx:
    image: emqx/emqx:5.7.1
    environment:
      EMQX_NODE__COOKIE: irrigatore_dev_cookie
      EMQX_DASHBOARD__DEFAULT_PASSWORD: changeme_dev
    ports:
      - "1883:1883"     # MQTT (dev, sin TLS)
      - "8883:8883"     # MQTT sobre TLS
      - "18083:18083"   # dashboard de EMQX
    volumes:
      - emqxdata:/opt/emqx/data
      - ./infra/emqx/certs:/opt/emqx/etc/certs:ro
    healthcheck:
      test: ["CMD", "/opt/emqx/bin/emqx", "ctl", "status"]
      interval: 10s
      timeout: 5s
      retries: 5
```

Y agregar a `volumes:`:

```yaml
  emqxdata:
```

- [ ] **Step 3: Documentar la generación de certs TLS de dev**

```markdown
<!-- infra/emqx/README.md -->
# EMQX — certificados TLS de desarrollo

Generar certificados autofirmados para el listener `8883` (mqtts):

```bash
mkdir -p infra/emqx/certs && cd infra/emqx/certs
openssl req -x509 -newkey rsa:2048 -nodes -keyout key.pem -out cert.pem \
  -days 365 -subj "/CN=localhost"
```

EMQX monta `infra/emqx/certs` en `/opt/emqx/etc/certs`. Para producción se
reemplazan por certificados reales (Let's Encrypt). Los `.pem` están ignorados
por git (ver `.gitignore`).
```

- [ ] **Step 4: Ignorar los certs en git**

Agregar a `.gitignore`:

```
infra/emqx/certs/*.pem
```

- [ ] **Step 5: Levantar y verificar EMQX**

```bash
docker compose up -d emqx
docker compose exec emqx /opt/emqx/bin/emqx ctl status
```
Expected: imprime que el nodo está `started`.

- [ ] **Step 6: Commit**

```bash
git add docker-compose.yml infra/emqx/README.md .env.example .gitignore
git commit -m "chore: sumar broker EMQX al Docker Compose con listener TLS"
```

---

## Task 8: CI con GitHub Actions

**Files:**
- Create: `.github/workflows/ci.yml`

- [ ] **Step 1: Escribir el workflow de CI**

```yaml
# .github/workflows/ci.yml
name: CI

on:
  push:
    branches: [main, "feat/**"]
  pull_request:

jobs:
  build:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: timescale/timescaledb:2.16.1-pg16
        env:
          POSTGRES_USER: irrigatore
          POSTGRES_PASSWORD: changeme_dev
          POSTGRES_DB: irrigatore
        ports:
          - 5432:5432
        options: >-
          --health-cmd "pg_isready -U irrigatore -d irrigatore"
          --health-interval 5s --health-timeout 5s --health-retries 10
    env:
      DATABASE_URL: postgres://irrigatore:changeme_dev@localhost:5432/irrigatore
    steps:
      - uses: actions/checkout@v4
      - uses: pnpm/action-setup@v4
        with:
          version: 9
      - uses: actions/setup-node@v4
        with:
          node-version: 20
          cache: pnpm
      - run: pnpm install --frozen-lockfile
      - run: pnpm typecheck
      - run: pnpm --filter @irrigatore/backend db:migrate
      - run: pnpm --filter @irrigatore/backend db:seed
      - run: pnpm test
```

- [ ] **Step 2: Verificar la sintaxis del workflow localmente**

Run: `pnpm dlx @action-validator/cli .github/workflows/ci.yml` (o revisión manual si no está disponible)
Expected: sin errores de sintaxis.

- [ ] **Step 3: Commit y push para disparar el CI**

```bash
git add .github/workflows/ci.yml
git commit -m "ci: lint, typecheck, migraciones y tests en GitHub Actions"
git push -u origin feat/v2-foundations
```

- [ ] **Step 4: Verificar que el CI pasa**

Run: `gh run watch`
Expected: el job `build` termina en verde.

---

## Definition of Done (Fase 0)

- [ ] `pnpm install` funciona desde cero en el monorepo.
- [ ] `docker compose up -d` levanta Postgres/Timescale + EMQX, ambos `healthy`.
- [ ] `pnpm --filter @irrigatore/backend db:migrate` crea las tablas y la hypertable `readings`.
- [ ] `pnpm --filter @irrigatore/backend db:seed` deja un device de dev con dos zonas.
- [ ] `pnpm test` pasa en verde (shared + backend).
- [ ] El CI corre y pasa en GitHub Actions.
- [ ] El contrato de telemetría (`@irrigatore/shared`) está listo para que el plan de Fase 1 lo consuma.
```

