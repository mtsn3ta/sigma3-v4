<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {

            $table->id();

            $table->string('nama_kelas', 50);

            $table->unsignedTinyInteger('tingkat');

            $table->enum('status', [
                'aktif',
                'nonaktif',
            ])->default('aktif');

            $table->timestamps();

            $table->unique('nama_kelas');
            $table->index('tingkat');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
