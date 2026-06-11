import mqtt from "mqtt";
import { TelemetryPayloadSchema, type TelemetryPayload } from "@irrigatore/shared";

const MQTT_URL = process.env.MQTT_URL ?? "mqtt://localhost:1883";
const DEVICE_KEY = process.env.DEVICE_KEY ?? "dev-device-001";
const INTERVAL_MS = Number(process.env.INTERVAL_MS ?? 3000);

// Estado mutable local del simulador (random walk).
const state = {
  air_temp: 24, air_humidity: 60, pressure: 1013,
  z1: 40, z2: 50,
};

function drift(v: number, step: number, min: number, max: number): number {
  const next = v + (Math.sin(Date.now() / 5000 + v) * step);
  return Math.max(min, Math.min(max, Number(next.toFixed(2))));
}

function buildPayload(): TelemetryPayload {
  state.air_temp = drift(state.air_temp, 0.5, 5, 40);
  state.air_humidity = drift(state.air_humidity, 1, 20, 95);
  state.pressure = drift(state.pressure, 0.3, 990, 1030);
  state.z1 = drift(state.z1, 1.5, 0, 100);
  state.z2 = drift(state.z2, 1.5, 0, 100);
  const payload: TelemetryPayload = {
    ts: Math.floor(Date.now() / 1000),
    fw: "sim-1.0.0",
    device: { air_temp: state.air_temp, air_humidity: state.air_humidity, pressure: state.pressure },
    zones: [
      { zone: "z1", soil_moisture: state.z1, valve: state.z1 < 30 },
      { zone: "z2", soil_moisture: state.z2, valve: state.z2 < 30 },
    ],
  };
  // Auto-chequeo: el simulador nunca debe emitir un payload que el backend rechazaría.
  TelemetryPayloadSchema.parse(payload);
  return payload;
}

async function main() {
  const client = await mqtt.connectAsync(MQTT_URL, { rejectUnauthorized: false });
  const topic = `irrigatore/${DEVICE_KEY}/telemetry`;
  console.log(`[sim] conectado a ${MQTT_URL}, publicando en ${topic} cada ${INTERVAL_MS}ms`);
  setInterval(async () => {
    const payload = buildPayload();
    await client.publishAsync(topic, JSON.stringify(payload), { qos: 1 });
    console.log(`[sim] publicado ts=${payload.ts} z1=${payload.zones[0].soil_moisture} z2=${payload.zones[1].soil_moisture}`);
  }, INTERVAL_MS);
}

main().catch((err) => { console.error(err); process.exit(1); });
