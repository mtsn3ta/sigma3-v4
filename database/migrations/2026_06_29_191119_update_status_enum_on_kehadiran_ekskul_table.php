<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE kehadiran_ekskul
            MODIFY COLUMN status
            ENUM('hadir','tidak_hadir')
            NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE kehadiran_ekskul
            MODIFY COLUMN status
            ENUM('hadir','izin','sakit','alpa')
            NOT NULL
        ");
    }
};
