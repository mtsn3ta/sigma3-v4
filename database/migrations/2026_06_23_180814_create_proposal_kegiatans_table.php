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
        Schema::create('proposal_kegiatan', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ekstrakurikuler_id')
                ->constrained('ekstrakurikuler')
                ->cascadeOnDelete();

            $table->string('nama_kegiatan', 150);

            $table->string('jenis_kegiatan', 150);

            $table->string('tempat', 150);

            $table->date('tanggal_mulai');

            $table->date('tanggal_selesai');

            $table->longText('latar_belakang')->nullable();

            $table->longText('dasar_kegiatan')->nullable();

            $table->longText('tujuan')->nullable();

            $table->longText('penutup')->nullable();

            $table->enum('status', [
                'draft',
                'pending_pembina',
                'pending_waka',
                'pending_kepala',
                'approved',
                'rejected',
            ])->default('draft');

            $table->index('status');

            $table->index('ekstrakurikuler_id');

            $table->timestamp('submitted_at')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->foreignId('created_by_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_kegiatan');
    }
};
