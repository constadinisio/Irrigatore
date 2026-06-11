import {
  pgTable, uuid, text, timestamp, doublePrecision,
} from "drizzle-orm/pg-core";

export const users = pgTable("users", {
  id: uuid("id").primaryKey().defaultRandom(),
  email: text("email").notNull().unique(),
  passwordHash: text("password_hash").notNull(),
  createdAt: timestamp("created_at", { withTimezone: true }).notNull().defaultNow(),
});

export const devices = pgTable("devices", {
  id: uuid("id").primaryKey().defaultRandom(),
  userId: uuid("user_id").notNull().references(() => users.id),
  name: text("name").notNull(),
  deviceKey: text("device_key").notNull().unique(),
  status: text("status").notNull().default("offline"),
  fwVersion: text("fw_version"),
  lastSeenAt: timestamp("last_seen_at", { withTimezone: true }),
});

export const zones = pgTable("zones", {
  id: uuid("id").primaryKey().defaultRandom(),
  deviceId: uuid("device_id").notNull().references(() => devices.id),
  name: text("name").notNull(),
  cropType: text("crop_type"),
  createdAt: timestamp("created_at", { withTimezone: true }).notNull().defaultNow(),
});

// Formato narrow: una fila por métrica. zoneId NULL = métrica ambiental del device.
export const readings = pgTable("readings", {
  time: timestamp("time", { withTimezone: true }).notNull(),
  deviceId: uuid("device_id").notNull().references(() => devices.id),
  zoneId: uuid("zone_id").references(() => zones.id),
  metric: text("metric").notNull(),
  value: doublePrecision("value").notNull(),
});
