<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_kegiatan', function (Blueprint $table) {

            $table->string('nama_penyelenggara')
                ->after('jenis_kegiatan');

            $table->enum('tingkat_kegiatan', [
                'kecamatan',
                'kabupaten',
                'karesidenan',
                'provinsi',
                'nasional',
                'internasional',
            ])->after('nama_penyelenggara');

            $table->string('nomor_surat_undangan')
                ->nullable()
                ->after('tingkat_kegiatan');

            $table->date('tanggal_surat_undangan')
                ->nullable()
                ->after('nomor_surat_undangan');

            $table->string('surat_undangan')
                ->nullable()
                ->after('tanggal_surat_undangan');

            $table->string('brosur')
                ->nullable()
                ->after('surat_undangan');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_kegiatan', function (Blueprint $table) {

            $table->dropColumn([
                'nama_penyelenggara',
                'tingkat_kegiatan',
                'nomor_surat_undangan',
                'tanggal_surat_undangan',
                'surat_undangan',
                'brosur',
            ]);
        });
    }
};
