import Fastify, { type FastifyInstance } from "fastify";
import { readingsRoutes } from "./readings-routes.js";
import { liveWs } from "../ws/live.js";

export async function buildServer(): Promise<FastifyInstance> {
  const app = Fastify({ logger: false });
  await app.register(liveWs);
  await app.register(readingsRoutes);
  return app;
}
