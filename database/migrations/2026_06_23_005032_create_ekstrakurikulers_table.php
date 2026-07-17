<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ekstrakurikuler', function (Blueprint $table) {

            $table->id();

            $table->string('nama', 150);

            $table->text('deskripsi')
                ->nullable();

            $table->json('jadwal')->nullable();

            $table->integer('kuota')
                ->nullable();

            $table->enum('status', [
                'aktif',
                'nonaktif',
            ])->default('aktif');

            $table->timestamps();

            $table->index('nama');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ekstrakurikuler');
    }
};
