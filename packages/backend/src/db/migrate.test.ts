import { describe, it, expect, afterAll } from "vitest";
import postgres from "postgres";

// Requiere DATABASE_URL apuntando a una DB de test (docker compose up postgres).
const url = process.env.DATABASE_URL!;
const sql = postgres(url, { max: 1 });

afterAll(async () => { await sql.end(); });

describe("hypertable readings", () => {
  it("readings está registrada como hypertable de Timescale", async () => {
    const rows = await sql`
      SELECT hypertable_name FROM timescaledb_information.hypertables
      WHERE hypertable_name = 'readings'`;
    expect(rows.length).toBe(1);
  });
});
