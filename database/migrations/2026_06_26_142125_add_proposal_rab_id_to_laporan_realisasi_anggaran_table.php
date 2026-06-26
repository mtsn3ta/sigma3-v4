<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_realisasi_anggaran', function (Blueprint $table) {

            $table->foreignId('proposal_rab_id')
                ->nullable()
                ->after('laporan_kegiatan_id')
                ->constrained('proposal_rab')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('laporan_realisasi_anggaran', function (Blueprint $table) {

            $table->dropConstrainedForeignId('proposal_rab_id');
        });
    }
};
