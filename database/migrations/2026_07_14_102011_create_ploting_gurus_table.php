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
        Schema::create('ploting_gurus', function (Blueprint $table) {
            $table->id();

            // Relasi ke tahun ajaran yang aktif
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();

            // Relasi ke data master pegawai (tabel 'pegawai' tanpa 's')
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();

            $table->string('kode_guru'); // Dinamis per tahun (misal: 01, 21, SI)
            $table->string('mapel');     // Mapel yang diampu (misal: Matematika, TIK)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ploting_gurus');
    }
};
