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
        if (!Schema::hasColumn('kelas', 'qr_token')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->string('qr_token', 64)->nullable()->unique()->after('status');
            });
        }

        if (!Schema::hasTable('presensi_guru_kelas')) {
            Schema::create('presensi_guru_kelas', function (Blueprint $table) {
                $table->id();
                $table->date('tanggal');
                $table->string('jam_ke', 20);
                $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
                $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
                $table->foreignId('jadwal_pelajaran_id')->nullable()->constrained('jadwal_pelajarans')->nullOnDelete();
                $table->enum('metode', ['qr_code', 'rfid', 'jurnal'])->default('qr_code');
                $table->dateTime('waktu_masuk');
                $table->boolean('is_guru_pengganti')->default(false);
                $table->string('catatan', 255)->nullable();
                $table->timestamps();

                $table->index(['tanggal', 'kelas_id']);
                $table->index(['tanggal', 'pegawai_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_guru_kelas');

        if (Schema::hasColumn('kelas', 'qr_token')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->dropColumn('qr_token');
            });
        }
    }
};
