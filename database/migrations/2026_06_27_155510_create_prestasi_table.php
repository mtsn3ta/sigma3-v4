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
        Schema::create('prestasi', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ekstrakurikuler_id')
                ->nullable()
                ->constrained('ekstrakurikuler')
                ->nullOnDelete();

            $table->string('nama_prestasi', 150);

            $table->string('nama_lomba', 150);

            $table->enum('jenis', [
                'individu',
                'tim',
            ]);

            $table->string('tingkat', 100);

            $table->string('peringkat', 100);

            $table->date('tanggal');

            $table->longText('keterangan')
                ->nullable();

            $table->string('foto')
                ->nullable();

            $table->foreignId('created_by_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index('ekstrakurikuler_id');
            $table->index('tanggal');
            $table->index('jenis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};
