<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_pelajarans', function (Blueprint $table) {
            // 1. Hapus kunci tamu (foreign key) dan kolom lama
            $table->dropForeign(['pegawai_id']);
            $table->dropColumn(['pegawai_id', 'mapel']);

            // 2. Tambahkan kolom baru yang mengarah ke Ploting Guru
            $table->foreignId('ploting_guru_id')
                ->after('kelas_id') // Posisikan setelah kolom kelas_id agar rapi
                ->constrained('ploting_gurus')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_pelajarans', function (Blueprint $table) {
            // Kembalikan ke kondisi semula jika terjadi rollback
            $table->dropForeign(['ploting_guru_id']);
            $table->dropColumn('ploting_guru_id');

            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->string('mapel');
        });
    }
};
