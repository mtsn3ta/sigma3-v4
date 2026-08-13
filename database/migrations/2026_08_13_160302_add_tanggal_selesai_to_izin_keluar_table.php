<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('izin_keluar', function (Blueprint $table) {
            // Menambahkan kolom 'tanggal_selesai' bertipe date, boleh kosong (nullable),
            // diletakkan tepat setelah kolom 'tanggal' yang sudah ada.
            $table->date('tanggal_selesai')
                ->nullable()
                ->after('tanggal')
                ->comment('Tanggal berakhirnya izin / dispensasi (jika > 1 hari)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('izin_keluar', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn('tanggal_selesai');
        });
    }
};
