<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE sesi_kegiatan MODIFY waktu_mulai TIME NULL");
        DB::statement("ALTER TABLE sesi_kegiatan MODIFY waktu_selesai TIME NULL");
        DB::statement("ALTER TABLE sesi_kegiatan MODIFY status ENUM('draft', 'aktif', 'selesai', 'batal') NOT NULL DEFAULT 'aktif'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE sesi_kegiatan MODIFY waktu_mulai TIME NOT NULL");
        DB::statement("ALTER TABLE sesi_kegiatan MODIFY waktu_selesai TIME NOT NULL");
        DB::statement("ALTER TABLE sesi_kegiatan MODIFY status ENUM('aktif', 'selesai', 'batal') NOT NULL DEFAULT 'aktif'");
    }
};
