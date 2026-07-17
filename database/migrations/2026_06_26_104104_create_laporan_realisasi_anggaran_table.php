<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_realisasi_anggaran', function (Blueprint $table) {

            $table->id();

            $table->foreignId('laporan_kegiatan_id')
                ->constrained('laporan_kegiatan')
                ->cascadeOnDelete();

            $table->string('uraian', 255);

            $table->unsignedInteger('qty')
                ->default(1);

            $table->string('satuan', 50);

            $table->decimal('harga_satuan', 15, 2);

            $table->decimal('subtotal', 15, 2)
                ->default(0);

            // Nota / Kwitansi / Invoice / Bukti Transfer
            $table->string('bukti')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_realisasi_anggaran');
    }
};
