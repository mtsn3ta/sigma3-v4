<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ekstrakurikuler_pengampu', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ekstrakurikuler_id')
                ->constrained('ekstrakurikuler')
                ->cascadeOnDelete();

            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();

            $table->enum('role', [
                'pembina',
                'pelatih',
            ]);

            $table->timestamps();

            $table->index('ekstrakurikuler_id');
            $table->index('pegawai_id');

            $table->unique(
                [
                    'ekstrakurikuler_id',
                    'pegawai_id',
                    'role',
                ],
                'ep_role_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ekstrakurikuler_pengampu');
    }
};
