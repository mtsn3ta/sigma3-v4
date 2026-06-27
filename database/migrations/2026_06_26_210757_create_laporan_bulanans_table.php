<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_bulanan', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ekstrakurikuler_id')
                ->constrained('ekstrakurikuler')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('bulan');

            $table->unsignedSmallInteger('tahun');

            $table->foreignId('created_by_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | BAB I
            |--------------------------------------------------------------------------
            */

            $table->longText('pendahuluan')->nullable();

            $table->longText('tujuan')->nullable();

            /*
            |--------------------------------------------------------------------------
            | BAB IV
            |--------------------------------------------------------------------------
            */

            $table->longText('evaluasi')->nullable();

            $table->longText('kendala')->nullable();

            $table->longText('solusi')->nullable();

            /*
            |--------------------------------------------------------------------------
            | BAB V
            |--------------------------------------------------------------------------
            */

            $table->longText('penutup')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Lampiran
            |--------------------------------------------------------------------------
            */

            $table->json('dokumentasi')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Workflow
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'draft',
                'pending_pembina',
                'pending_waka',
                'pending_kepala',
                'revision',
                'approved',
                'rejected',
            ])->default('draft');

            $table->timestamp('submitted_at')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Constraint
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'ekstrakurikuler_id',
                'bulan',
                'tahun',
            ]);

            $table->index('status');

            $table->index('created_by_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_bulanan');
    }
};
