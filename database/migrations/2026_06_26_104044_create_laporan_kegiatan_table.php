<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_kegiatan', function (Blueprint $table) {

            $table->id();

            $table->foreignId('proposal_kegiatan_id')
                ->unique()
                ->constrained('proposal_kegiatan')
                ->cascadeOnDelete();

            $table->foreignId('created_by_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->longText('uraian_pelaksanaan')->nullable();

            $table->longText('pencapaian')->nullable();

            $table->longText('prestasi')->nullable();

            $table->longText('evaluasi')->nullable();

            $table->longText('penutup')->nullable();

            $table->json('dokumentasi')->nullable();

            $table->string('sertifikat')->nullable();

            $table->string('berita_acara')->nullable();

            $table->enum('status', [
                'draft',
                'pending_pembina',
                'pending_waka',
                'pending_kepala',
                'revision',
                'approved',
                'rejected',
            ])->default('draft');

            $table->index('status');
            $table->index('created_by_id');

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kegiatan');
    }
};
