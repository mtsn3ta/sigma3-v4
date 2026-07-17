<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('aksi', 150);

            $table->longText('deskripsi')
                ->nullable();

            $table->string('ip_address', 100)
                ->nullable();

            $table->longText('user_agent')
                ->nullable();

            $table->timestamp('created_at')
                ->useCurrent();

            $table->index('user_id');
            $table->index('aksi');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
