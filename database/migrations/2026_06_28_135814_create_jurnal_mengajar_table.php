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
        Schema::create('jurnal_mengajar', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->date('tanggal');

            $table->string('jam_ke', 20);

            $table->string('mapel', 100);

            $table->longText('materi');

            $table->longText('siswa_tidak_hadir')
                ->nullable();

            $table->longText('catatan')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'pegawai_id',
                'kelas_id',
                'tanggal',
                'jam_ke',
            ], 'jurnal_mengajar_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_mengajar');
    }
};
