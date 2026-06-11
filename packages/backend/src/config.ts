import { z } from "zod";

const EnvSchema = z.object({
  DATABASE_URL: z.string().min(1),
  MQTT_URL: z.string().min(1).default("mqtt://localhost:1883"),
  PORT: z.coerce.number().int().positive().default(3000),
});

export interface Config {
  databaseUrl: string;
  mqttUrl: string;
  port: number;
}

export function loadConfig(env: NodeJS.ProcessEnv = process.env): Config {
  const parsed = EnvSchema.parse(env);
  return { databaseUrl: parsed.DATABASE_URL, mqttUrl: parsed.MQTT_URL, port: parsed.PORT };
}
