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
