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
        Schema::create('proposal_rab', function (Blueprint $table) {

            $table->id();

            $table->foreignId('proposal_kegiatan_id')
                ->constrained('proposal_kegiatan')
                ->cascadeOnDelete();

            $table->string('uraian', 150);

            $table->integer('qty')->default(1);

            $table->string('satuan', 50)->default('item');

            $table->decimal('harga_satuan', 15, 2)->default(0);

            $table->decimal('subtotal', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_rab');
    }
};
