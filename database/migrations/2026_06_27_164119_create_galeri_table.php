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
        Schema::create('galeri', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ekstrakurikuler_id')
                ->nullable()
                ->constrained('ekstrakurikuler')
                ->nullOnDelete();

            $table->string('judul', 150);

            $table->longText('deskripsi')
                ->nullable();

            $table->date('tanggal');

            $table->enum('kategori', [
                'sekolah',
                'ekstrakurikuler',
                'prestasi',
                'kegiatan',
            ]);

            $table->foreignId('created_by_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index('ekstrakurikuler_id');
            $table->index('kategori');
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri');
    }
};
