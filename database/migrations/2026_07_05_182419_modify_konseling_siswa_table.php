<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konseling_siswa', function (Blueprint $table) {
            // Jangan panggil foreignId('pegawai_id') lagi.
            // Cukup ambil kolom yang ada, lalu modifikasi sifatnya menjadi nullable.
            $table->foreignId('pegawai_id')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('konseling_siswa', function (Blueprint $table) {
            $table->foreignId('pegawai_id')
                ->nullable(false)
                ->change();
        });
    }
};
