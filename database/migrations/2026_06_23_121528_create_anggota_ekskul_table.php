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
        Schema::create('anggota_ekskul', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->foreignId('ekstrakurikuler_id')
                ->constrained('ekstrakurikuler')
                ->cascadeOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->cascadeOnDelete();

            $table->date('tanggal_daftar');

            $table->enum('status', [
                'aktif',
                'nonaktif',
            ])->default('aktif');

            $table->timestamps();

            $table->index('siswa_id');
            $table->index('ekstrakurikuler_id');
            $table->index('tahun_ajaran_id');

            $table->unique(
                [
                    'siswa_id',
                    'ekstrakurikuler_id',
                    'tahun_ajaran_id',
                ],
                'anggota_ekskul_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_ekskul');
    }
};
