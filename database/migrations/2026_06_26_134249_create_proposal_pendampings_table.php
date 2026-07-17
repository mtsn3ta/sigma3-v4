<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_pendamping', function (Blueprint $table) {

            $table->id();

            $table->foreignId('proposal_kegiatan_id')
                ->constrained('proposal_kegiatan')
                ->cascadeOnDelete();

            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->cascadeOnDelete();

            $table->enum('peran', [
                'pelatih',
                'pembina',
                'guru_pendamping',
                'official',
                'medis',
                'transportasi',
                'lainnya',
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_pendamping');
    }
};
