import { loadConfig } from "./config.js";
import { startMqttWorker } from "./mqtt/worker.js";
import { buildServer } from "./api/server.js";

async function main() {
  const cfg = loadConfig();
  const app = await buildServer();
  const mqttClient = await startMqttWorker(cfg.mqttUrl);

  await app.listen({ port: cfg.port, host: "0.0.0.0" });
  console.log(`[backend] API + WS escuchando en :${cfg.port}`);

  const shutdown = async () => {
    console.log("[backend] cerrando...");
    await app.close();
    await mqttClient.endAsync();
    process.exit(0);
  };
  process.on("SIGINT", shutdown);
  process.on("SIGTERM", shutdown);
}

main().catch((err) => { console.error(err); process.exit(1); });
