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
