import { describe, it, expect, afterAll } from "vitest";
import postgres from "postgres";
import { requireDb } from "./test-helpers.js";

const url = process.env.DATABASE_URL;
const sql = postgres(url ?? "postgres://x", { max: 1 });

afterAll(async () => { await sql.end(); });

requireDb(describe)("hypertable readings", () => {
  it("readings está registrada como hypertable de Timescale", async () => {
    const rows = await sql`
      SELECT hypertable_name FROM timescaledb_information.hypertables
      WHERE hypertable_name = 'readings'`;
    expect(rows.length).toBe(1);
  });
});
