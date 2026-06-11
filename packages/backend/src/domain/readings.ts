import type { TelemetryPayload, Metric } from "@irrigatore/shared";

export interface ReadingRow {
  time: Date;
  deviceId: string;
  zoneId: string | null;
  metric: Metric;
  value: number;
}

/**
 * Convierte un payload validado en filas narrow.
 * - Métricas ambientales del device → zoneId null.
 * - soil_moisture → una fila por zona cuyo nombre exista en zoneIdByName.
 *   Zonas desconocidas se descartan (no se inventan).
 */
export function mapTelemetryToReadings(
  payload: TelemetryPayload,
  deviceId: string,
  zoneIdByName: Map<string, string>,
): ReadingRow[] {
  const time = new Date(payload.ts * 1000);
  const rows: ReadingRow[] = [];

  const deviceMetrics = ["air_temp", "air_humidity", "pressure"] as const;
  for (const metric of deviceMetrics) {
    rows.push({ time, deviceId, zoneId: null, metric, value: payload.device[metric] });
  }

  for (const z of payload.zones) {
    const zoneId = zoneIdByName.get(z.zone);
    if (!zoneId) continue; // zona desconocida → descartar
    rows.push({ time, deviceId, zoneId, metric: "soil_moisture", value: z.soil_moisture });
  }

  return rows;
}
