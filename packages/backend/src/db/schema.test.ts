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
