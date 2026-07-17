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
        Schema::create('permasalahan_siswa', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();

            $table->date('tanggal')->index();

            $table->longText('permasalahan');

            $table->longText('penyelesaian');

            $table->integer('poin')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permasalahan_siswa');
    }
};
