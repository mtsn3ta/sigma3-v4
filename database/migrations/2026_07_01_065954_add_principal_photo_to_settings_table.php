<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->string('kepala_madrasah_photo')
                ->nullable()
                ->after('favicon');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->dropColumn('kepala_madrasah_photo');
        });
    }
};
