import { describe, it, expect, afterAll } from "vitest";
import postgres from "postgres";
import { requireDb } from "./test-helpers.js";

const url = process.env.DATABASE_URL;
const sql = postgres(url ?? "postgres://x", { max: 1 });
afterAll(async () => { await sql.end(); });

requireDb(describe)("tracking de migraciones", () => {
  it("registra cada migración aplicada en schema_migrations", async () => {
    const rows = await sql`SELECT filename FROM schema_migrations ORDER BY filename`;
    const names = rows.map((r) => r.filename as string);
    expect(names).toContain("0000_spotty_caretaker.sql");
    expect(names).toContain("0001_hypertable.sql");
  });
});
