<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revision_logs', function (Blueprint $table) {

            $table->id();

            $table->morphs('revisionable');

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('status_from', 50);

            $table->string('status_to', 50);

            $table->longText('notes');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revision_logs');
    }
};
