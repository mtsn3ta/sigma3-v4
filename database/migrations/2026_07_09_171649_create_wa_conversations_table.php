<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wa_conversations', function (Blueprint $table) {

            $table->id();

            $table->string('phone')->unique();

            $table->string('state')->default('menu');

            $table->string('menu')->nullable();

            $table->json('data')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wa_conversations');
    }
};
