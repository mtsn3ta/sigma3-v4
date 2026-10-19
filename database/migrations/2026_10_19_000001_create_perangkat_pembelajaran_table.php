<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perangkat_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')
                ->nullable()
                ->constrained('tahun_ajaran')
                ->nullOnDelete();
            $table->foreignId('kelas_id')
                ->nullable()
                ->constrained('kelas')
                ->nullOnDelete();
            $table->string('mapel', 100)->nullable();
            $table->string('judul', 200);
            $table->enum('jenis', ['RPP', 'Modul Ajar', 'Silabus', 'Prota', 'Prosem', 'Bahan Ajar', 'Lainnya'])->default('Modul Ajar');
            $table->string('file_path');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perangkat_pembelajaran');
    }
};
