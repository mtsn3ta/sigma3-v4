<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadiran_ibadah', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->enum('jenis', [

                'dhuha',

                'dzuhur',

            ]);

            $table->enum('status', [

                'hadir',

                'tidak_hadir',

            ]);

            $table->enum('sumber', [

                'rfid',

                'manual',

            ]);

            $table->foreignId('petugas_id')
                ->nullable()
                ->constrained('pegawai')
                ->nullOnDelete();

            $table->dateTime('waktu_scan')
                ->nullable();

            $table->timestamps();

            $table->index([
                'siswa_id',
                'tanggal',
            ]);

            $table->unique([
                'siswa_id',
                'tanggal',
                'jenis',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran_ibadah');
    }
};
