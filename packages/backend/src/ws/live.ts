import type { FastifyInstance } from "fastify";
import websocket from "@fastify/websocket";
import { bus, type TelemetryEvent } from "../bus.js";

export async function liveWs(app: FastifyInstance): Promise<void> {
  await app.register(websocket);
  app.register(async (scoped) => {
    scoped.get<{ Params: { deviceKey: string } }>(
      "/ws/:deviceKey",
      { websocket: true },
      (socket, req) => {
        const { deviceKey } = req.params;
        const listener = (ev: TelemetryEvent) => {
          if (ev.deviceKey !== deviceKey) return;
          socket.send(JSON.stringify(ev));
        };
        bus.onTelemetry(listener);
        socket.on("close", () => bus.offTelemetry(listener));
      },
    );
  });
}
