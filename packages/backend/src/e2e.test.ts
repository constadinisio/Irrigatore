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
