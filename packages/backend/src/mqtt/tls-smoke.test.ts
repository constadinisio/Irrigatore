import { describe, it, expect, afterAll } from "vitest";
import mqtt from "mqtt";

// Solo corre si se pide explícitamente (necesita EMQX con TLS levantado).
const runTls = process.env.RUN_TLS_SMOKE === "1";
const d = runTls ? describe : describe.skip;

let client: mqtt.MqttClient | undefined;
afterAll(async () => { await client?.endAsync(true); });

d("EMQX TLS (8883)", () => {
  it("conecta por mqtts, publica y recibe un mensaje", async () => {
    client = await mqtt.connectAsync("mqtts://localhost:8883", {
      rejectUnauthorized: false, // cert autofirmado de dev
      connectTimeout: 5000,
    });
    const topic = "irrigatore/_smoke/tls";
    await client.subscribeAsync(topic);
    const received = new Promise<string>((resolve) => {
      client!.on("message", (_t, payload) => resolve(payload.toString()));
    });
    await client.publishAsync(topic, "ok", { qos: 1 });
    await expect(received).resolves.toBe("ok");
  });
});
