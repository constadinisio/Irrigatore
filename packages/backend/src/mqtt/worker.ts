import mqtt from "mqtt";
import { TelemetryPayloadSchema } from "@irrigatore/shared";
import { mapTelemetryToReadings } from "../domain/readings.js";
import { resolveDevice, getZoneIdMap, insertReadings } from "../db/readings-repo.js";
import { bus } from "../bus.js";

const TELEMETRY_RE = /^irrigatore\/([^/]+)\/telemetry$/;

/** Procesa un único mensaje de telemetría. Nunca lanza: loguea y descarta. */
export async function handleMessage(topic: string, raw: Buffer): Promise<void> {
  const m = TELEMETRY_RE.exec(topic);
  if (!m) return;
  const deviceKey = m[1];

  let json: unknown;
  try {
    json = JSON.parse(raw.toString());
  } catch {
    console.warn(`[mqtt] JSON inválido en ${topic}, descartado`);
    return;
  }

  const parsed = TelemetryPayloadSchema.safeParse(json);
  if (!parsed.success) {
    console.warn(`[mqtt] payload inválido en ${topic}: ${parsed.error.message}`);
    return;
  }

  try {
    const device = await resolveDevice(deviceKey);
    if (!device) {
      console.warn(`[mqtt] device desconocido '${deviceKey}', descartado`);
      return;
    }
    const zoneMap = await getZoneIdMap(device.id);
    const rows = mapTelemetryToReadings(parsed.data, device.id, zoneMap);
    await insertReadings(rows);
    bus.emitTelemetry({ deviceKey, payload: parsed.data });
  } catch (err) {
    console.error(`[mqtt] error persistiendo telemetría de '${deviceKey}':`, err);
  }
}

/** Arranca el worker: conecta a EMQX y se suscribe a la telemetría de todos los devices. */
export async function startMqttWorker(mqttUrl: string): Promise<mqtt.MqttClient> {
  const client = await mqtt.connectAsync(mqttUrl, {
    reconnectPeriod: 2000,
    rejectUnauthorized: false, // dev: cert autofirmado si se usa mqtts
  });
  await client.subscribeAsync("irrigatore/+/telemetry", { qos: 1 });
  client.on("message", (topic, payload) => { void handleMessage(topic, payload); });
  client.on("error", (err) => console.error("[mqtt] error de conexión:", err));
  console.log(`[mqtt] worker conectado a ${mqttUrl}, suscripto a irrigatore/+/telemetry`);
  return client;
}
