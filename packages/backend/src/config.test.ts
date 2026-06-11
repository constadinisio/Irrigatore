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
