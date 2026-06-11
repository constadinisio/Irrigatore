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
