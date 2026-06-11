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
