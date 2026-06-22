<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas_siswa', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->cascadeOnDelete();

            $table->enum('status', [
                'aktif',
                'naik',
                'lulus',
            ])->default('aktif');

            $table->timestamps();

            $table->index('siswa_id');
            $table->index('kelas_id');
            $table->index('tahun_ajaran_id');

            $table->unique([
                'siswa_id',
                'tahun_ajaran_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_siswa');
    }
};
