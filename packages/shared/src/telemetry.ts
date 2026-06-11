import { z } from "zod";

export const ZoneReadingSchema = z.object({
  zone: z.string().min(1),
  soil_moisture: z.number(),
  valve: z.boolean(),
});

export const DeviceEnvSchema = z.object({
  air_temp: z.number(),
  air_humidity: z.number(),
  pressure: z.number(),
});

export const TelemetryPayloadSchema = z.object({
  ts: z.number().int().positive(),
  fw: z.string().min(1),
  device: DeviceEnvSchema,
  zones: z.array(ZoneReadingSchema).min(1),
});

export type ZoneReading = z.infer<typeof ZoneReadingSchema>;
export type DeviceEnv = z.infer<typeof DeviceEnvSchema>;
export type TelemetryPayload = z.infer<typeof TelemetryPayloadSchema>;

export const METRICS = ["soil_moisture", "air_temp", "air_humidity", "pressure"] as const;
export type Metric = (typeof METRICS)[number];
