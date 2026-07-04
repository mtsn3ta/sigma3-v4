<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {

            $table->string('tempat_lahir', 100)
                ->nullable()
                ->change();

            $table->date('tanggal_lahir')
                ->nullable()
                ->change();

            $table->text('alamat')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {

            $table->string('tempat_lahir', 100)
                ->nullable(false)
                ->change();

            $table->date('tanggal_lahir')
                ->nullable(false)
                ->change();

            $table->text('alamat')
                ->nullable(false)
                ->change();
        });
    }
};
