<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sesi_kegiatan', function (Blueprint $table) {

            $table->longText('program_latihan')
                ->nullable()
                ->after('waktu_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('sesi_kegiatan', function (Blueprint $table) {

            $table->dropColumn('program_latihan');
        });
    }
};
