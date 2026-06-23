<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadiran_ekskul', function (Blueprint $table) {

            $table->id();

            $table->foreignId('sesi_kegiatan_id')
                ->constrained('sesi_kegiatan')
                ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->enum('status', [
                'hadir',
                'izin',
                'sakit',
                'alpa',
            ]);

            $table->enum('sumber', [
                'rfid',
                'manual',
            ])->default('manual');

            $table->foreignId('petugas_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('waktu_scan')
                ->nullable();

            $table->timestamps();

            $table->index('sesi_kegiatan_id');
            $table->index('siswa_id');

            $table->unique(
                [
                    'sesi_kegiatan_id',
                    'siswa_id',
                ],
                'kehadiran_ekskul_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran_ekskul');
    }
};
