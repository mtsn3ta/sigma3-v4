<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rfid_failed_logs', function (Blueprint $table) {

            $table->id();

            $table->enum('mode', [
                'school',
                'ibadah',
                'ekskul',
            ]);

            $table->string('uid');

            $table->foreignId('siswa_id')
                ->nullable()
                ->constrained('siswa')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('error');

            $table->string('message');

            $table->ipAddress('ip_address')
                ->nullable();

            $table->timestamp('created_at')
                ->useCurrent();

            $table->index('mode');
            $table->index('uid');
            $table->index('error');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfid_failed_logs');
    }
};
