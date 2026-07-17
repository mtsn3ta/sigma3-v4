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
        Schema::create('sesi_kegiatan', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ekstrakurikuler_id')
                ->constrained('ekstrakurikuler')
                ->cascadeOnDelete();

            $table->foreignId('pelatih_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->time('waktu_mulai');

            $table->time('waktu_selesai');

            $table->enum('status', [
                'aktif',
                'selesai',
                'batal',
            ])->default('aktif');

            $table->timestamps();

            $table->index('ekstrakurikuler_id');
            $table->index('pelatih_id');
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_kegiatan');
    }
};
