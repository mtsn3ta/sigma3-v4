<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parent_leave_requests', function (Blueprint $table) {

            $table->renameColumn('tanggal', 'tanggal_mulai');

            $table->date('tanggal_selesai')
                ->after('tanggal_mulai');

            $table->boolean('punya_surat_dokter')
                ->default(false)
                ->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('parent_leave_requests', function (Blueprint $table) {

            $table->dropColumn([
                'tanggal_selesai',
                'punya_surat_dokter',
            ]);

            $table->renameColumn(
                'tanggal_mulai',
                'tanggal'
            );
        });
    }
};
