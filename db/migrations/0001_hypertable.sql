-- db/migrations/0001_hypertable.sql
CREATE EXTENSION IF NOT EXISTS timescaledb;
SELECT create_hypertable('readings', 'time', if_not_exists => TRUE, migrate_data => TRUE);
CREATE INDEX IF NOT EXISTS readings_device_time_idx ON readings (device_id, time DESC);
CREATE INDEX IF NOT EXISTS readings_zone_time_idx ON readings (zone_id, time DESC);
