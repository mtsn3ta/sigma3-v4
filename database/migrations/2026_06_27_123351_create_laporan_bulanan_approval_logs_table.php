<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_bulanan_approval_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('laporan_bulanan_id')
                ->constrained('laporan_bulanan')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('aksi', [
                'submit',
                'approve_pembina',
                'approve_waka',
                'approve_kepala',
                'revision',
                'reject',
            ]);

            $table->text('catatan')
                ->nullable();

            $table->timestamps();

            $table->index('laporan_bulanan_id');
            $table->index('user_id');
            $table->index('aksi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_bulanan_approval_logs');
    }
};
