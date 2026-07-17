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
        Schema::create('prestasi_siswa', function (Blueprint $table) {

            $table->id();

            $table->foreignId('prestasi_id')
                ->constrained('prestasi')
                ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'prestasi_id',
                'siswa_id',
            ]);

            $table->index('prestasi_id');
            $table->index('siswa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_siswa');
    }
};
