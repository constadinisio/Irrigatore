import type { FastifyInstance } from "fastify";
import { resolveDevice, getRecentReadings } from "../db/readings-repo.js";
import { METRICS, type Metric } from "@irrigatore/shared";

export async function readingsRoutes(app: FastifyInstance): Promise<void> {
  app.get<{
    Params: { deviceKey: string };
    Querystring: { metric?: string; limit?: string };
  }>("/api/devices/:deviceKey/readings", async (req, reply) => {
    const device = await resolveDevice(req.params.deviceKey);
    if (!device) return reply.code(404).send({ error: "device no encontrado" });

    const metric = req.query.metric as Metric | undefined;
    if (metric && !(METRICS as readonly string[]).includes(metric)) {
      return reply.code(400).send({ error: `metric inválida; válidas: ${METRICS.join(", ")}` });
    }
    const limit = req.query.limit ? Number(req.query.limit) : undefined;
    const rows = await getRecentReadings(device.id, { metric, limit });
    return reply.send(rows);
  });
}
