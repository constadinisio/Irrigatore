import { and, desc, eq, SQL } from "drizzle-orm";
import { db } from "./client.js";
import { devices, zones, readings } from "./schema.js";
import type { ReadingRow } from "../domain/readings.js";
import type { Metric } from "@irrigatore/shared";

export interface DeviceRef { id: string; deviceKey: string; }

export async function resolveDevice(deviceKey: string): Promise<DeviceRef | null> {
  const rows = await db
    .select({ id: devices.id, deviceKey: devices.deviceKey })
    .from(devices)
    .where(eq(devices.deviceKey, deviceKey))
    .limit(1);
  return rows[0] ?? null;
}

export async function getZoneIdMap(deviceId: string): Promise<Map<string, string>> {
  const rows = await db
    .select({ id: zones.id, name: zones.name })
    .from(zones)
    .where(eq(zones.deviceId, deviceId));
  return new Map(rows.map((z) => [z.name, z.id]));
}

export async function insertReadings(rows: ReadingRow[]): Promise<void> {
  if (rows.length === 0) return;
  await db.insert(readings).values(
    rows.map((r) => ({
      time: r.time,
      deviceId: r.deviceId,
      zoneId: r.zoneId,
      metric: r.metric as string,
      value: r.value,
    })),
  );
}

export interface RecentQuery { metric?: Metric; limit?: number; }

export async function getRecentReadings(
  deviceId: string,
  q: RecentQuery = {},
): Promise<Array<{ time: Date; metric: string; value: number; zoneId: string | null }>> {
  const limit = Math.min(q.limit ?? 100, 1000);

  // Build conditions array to avoid type mismatch between and(...) and eq(...)
  const conds: SQL[] = [eq(readings.deviceId, deviceId)];
  if (q.metric) {
    conds.push(eq(readings.metric, q.metric));
  }

  const rows = await db
    .select({ time: readings.time, metric: readings.metric, value: readings.value, zoneId: readings.zoneId })
    .from(readings)
    .where(and(...conds))
    .orderBy(desc(readings.time))
    .limit(limit);
  return rows;
}
