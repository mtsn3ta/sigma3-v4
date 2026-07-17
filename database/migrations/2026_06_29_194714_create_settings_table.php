<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {

            $table->id();

            $table->string('nama_madrasah');

            $table->longText('alamat');

            $table->string('telepon', 50);

            $table->string('email', 150);

            $table->string('logo')
                ->nullable();

            $table->string('favicon')
                ->nullable();

            $table->foreignId('kepala_aktif_id')
                ->nullable()
                ->constrained('pegawai')
                ->nullOnDelete();

            $table->foreignId('tahun_ajaran_aktif_id')
                ->nullable()
                ->constrained('tahun_ajaran')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
