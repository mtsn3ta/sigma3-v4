<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_kegiatan_pendamping', function (Blueprint $table) {

            $table->id();

            $table->foreignId('laporan_kegiatan_id')
                ->constrained('laporan_kegiatan')
                ->cascadeOnDelete();

            $table->foreignId('proposal_pendamping_id')
                ->nullable()
                ->constrained('proposal_pendamping')
                ->nullOnDelete();

            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();

            $table->enum('peran', [
                'pelatih',
                'pembina',
                'guru_pendamping',
                'official',
                'medis',
                'transportasi',
                'lainnya',
            ]);

            $table->enum('status', [
                'hadir',
                'izin',
                'sakit',
            ])->default('hadir');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kegiatan_pendamping');
    }
};
