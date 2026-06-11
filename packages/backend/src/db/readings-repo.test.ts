import { describe, it, expect, afterAll } from "vitest";
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
