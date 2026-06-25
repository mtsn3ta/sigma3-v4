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
        Schema::create('proposal_approval_log', function (Blueprint $table) {

            $table->id();

            $table->foreignId('proposal_kegiatan_id')
                ->constrained('proposal_kegiatan')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('action', [
                'submit',
                'approve_pembina',
                'approve_waka',
                'approve_kepala',
                'reject',
            ]);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_approval_log');
    }
};
