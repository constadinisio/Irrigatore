import postgres from "postgres";

async function run() {
  const url = process.env.DATABASE_URL;
  if (!url) throw new Error("DATABASE_URL no está definida");
  const sql = postgres(url, { max: 1 });
  try {
    const [user] = await sql`
      INSERT INTO users (email, password_hash)
      VALUES ('dev@irrigatore.local', 'x')
      ON CONFLICT (email) DO UPDATE SET email = EXCLUDED.email
      RETURNING id`;

    const [device] = await sql`
      INSERT INTO devices (user_id, name, device_key, status)
      VALUES (${user.id}, 'Placa de desarrollo', 'dev-device-001', 'offline')
      ON CONFLICT (device_key) DO UPDATE SET name = EXCLUDED.name
      RETURNING id`;

    for (const name of ["z1", "z2"]) {
      await sql`
        INSERT INTO zones (device_id, name, crop_type)
        VALUES (${device.id}, ${name}, 'tomate')
        ON CONFLICT DO NOTHING`;
    }
    console.log("Seed aplicado.");
  } finally {
    await sql.end();
  }
}

run().catch((err) => { console.error(err); process.exit(1); });
