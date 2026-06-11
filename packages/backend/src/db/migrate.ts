import { readdir, readFile } from "node:fs/promises";
import { join } from "node:path";
import postgres from "postgres";

const MIGRATIONS_DIR = join(process.cwd(), "../../db/migrations");

async function run() {
  const url = process.env.DATABASE_URL;
  if (!url) throw new Error("DATABASE_URL no está definida");
  const sql = postgres(url, { max: 1 });
  try {
    const files = (await readdir(MIGRATIONS_DIR))
      .filter((f) => f.endsWith(".sql"))
      .sort();
    for (const file of files) {
      const contents = await readFile(join(MIGRATIONS_DIR, file), "utf8");
      console.log(`Aplicando ${file}...`);
      await sql.unsafe(contents);
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
