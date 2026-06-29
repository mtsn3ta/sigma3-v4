<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_guru_izin', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete();

            $table->foreignId('petugas_id')
                ->nullable()
                ->constrained('pegawai')
                ->nullOnDelete();

            $table->date('tanggal');

            $table->string('mapel', 100);

            $table->string('jam_ke', 20);

            $table->longText('isi_tugas');

            $table->enum('status', [
                'baru',
                'disampaikan',
                'selesai',
            ])->default('baru');

            $table->timestamps();

            $table->index('pegawai_id');
            $table->index('petugas_id');
            $table->index('kelas_id');
            $table->index('tanggal');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_guru_izin');
    }
};
