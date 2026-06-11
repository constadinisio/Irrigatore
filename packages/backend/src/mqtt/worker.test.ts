import { describe, it, expect, vi, afterEach } from "vitest";

// vi.mock con factory explícita: evita que Vitest intente cargar el módulo real
// (que importa db/client.ts, el cual lanza si DATABASE_URL no está definida).
// Vitest eleva (hoist) esta llamada antes de los imports estáticos.
vi.mock("../db/readings-repo.js", () => ({
  resolveDevice: vi.fn(),
  getZoneIdMap: vi.fn(),
  insertReadings: vi.fn(),
}));

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
