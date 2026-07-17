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
        Schema::create('kehadiran_sekolah', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('tanggal');

            $table->enum('status', [
                'hadir',
                'terlambat',
                'izin',
                'sakit',
                'alfa',
            ])->comment('Status kehadiran final');

            $table->enum('sumber', [
                'rfid',
                'manual',
            ])->comment('Sumber pencatatan kehadiran');

            $table->time('jam_datang')
                ->nullable();

            $table->time('jam_pulang')
                ->nullable();

            $table->text('keterangan')
                ->nullable();

            $table->foreignId('petugas_id')
                ->nullable()
                ->constrained('pegawai')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();

            $table->index('siswa_id');

            $table->index('tanggal');

            $table->index('status');

            $table->unique(
                ['siswa_id', 'tanggal'],
                'kehadiran_sekolah_siswa_tanggal_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_sekolah');
    }
};
