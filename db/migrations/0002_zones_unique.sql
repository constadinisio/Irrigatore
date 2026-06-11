ALTER TABLE "zones" ADD CONSTRAINT "zones_device_id_name_unique" UNIQUE("device_id", "name");
