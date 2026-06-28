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
        Schema::create('izin_keluar', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();

            $table->date('tanggal')
                ->index();

            $table->time('jam_keluar');

            $table->time('jam_kembali')
                ->nullable();

            $table->dateTime('waktu_cetak');

            $table->string('tujuan', 150);

            $table->longText('keperluan');

            $table->enum('status_proses', [
                'keluar',
                'kembali',
                'pulang',
            ])->default('keluar');

            $table->timestamps();

            $table->index([
                'tanggal',
                'status_proses',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_keluar');
    }
};
