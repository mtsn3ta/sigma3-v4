<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('konseling_siswa', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();

            $table->date('tanggal')->index();

            $table->longText('permasalahan');

            $table->longText('hasil_konseling');

            $table->longText('tindak_lanjut');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konseling_siswa');
    }
};
