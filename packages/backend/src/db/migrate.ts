import { readdir, readFile } from "node:fs/promises";
import { join } from "node:path";
import postgres from "postgres";

const MIGRATIONS_DIR = join(process.cwd(), "../../db/migrations");

async function run() {
  const url = process.env.DATABASE_URL;
  if (!url) throw new Error("DATABASE_URL no está definida");
  const sql = postgres(url, { max: 1 });
  try {
    await sql`
      CREATE TABLE IF NOT EXISTS schema_migrations (
        filename text PRIMARY KEY,
        applied_at timestamptz NOT NULL DEFAULT now()
      )`;

    const applied = new Set(
      (await sql`SELECT filename FROM schema_migrations`).map((r) => r.filename as string),
    );

    const files = (await readdir(MIGRATIONS_DIR))
      .filter((f) => f.endsWith(".sql"))
      .sort();

    for (const file of files) {
      if (applied.has(file)) {
        console.log(`Saltando ${file} (ya aplicada).`);
        continue;
      }
      const contents = await readFile(join(MIGRATIONS_DIR, file), "utf8");
      console.log(`Aplicando ${file}...`);
      await sql.begin(async (tx) => {
        await tx.unsafe(contents);
        await tx`INSERT INTO schema_migrations (filename) VALUES (${file})`;
      });
    }
    console.log("Migraciones aplicadas.");
  } finally {
    await sql.end();
  }
}

run().catch((err) => {
  console.error(err);
  process.exit(1);
});
