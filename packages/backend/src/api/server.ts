import Fastify, { type FastifyInstance } from "fastify";
import { readingsRoutes } from "./readings-routes.js";

export async function buildServer(): Promise<FastifyInstance> {
  const app = Fastify({ logger: false });
  await app.register(readingsRoutes);
  return app;
}
