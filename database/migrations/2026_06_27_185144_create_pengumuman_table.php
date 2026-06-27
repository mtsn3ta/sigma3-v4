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
        Schema::create('pengumuman', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ekstrakurikuler_id')
                ->nullable()
                ->constrained('ekstrakurikuler')
                ->nullOnDelete();

            $table->string('judul', 150);

            $table->longText('isi');

            $table->string('lampiran')
                ->nullable();

            $table->enum('tipe', [
                'info',
                'success',
                'warning',
                'danger',
            ])->default('info');

            $table->date('tanggal_mulai');

            $table->date('tanggal_selesai');

            $table->enum('status', [
                'draft',
                'publish',
                'arsip',
            ])->default('draft');

            $table->boolean('is_pinned')
                ->default(false);

            $table->foreignId('created_by_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index('ekstrakurikuler_id');
            $table->index('status');
            $table->index('tanggal_mulai');
            $table->index('tanggal_selesai');
            $table->index('is_pinned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
