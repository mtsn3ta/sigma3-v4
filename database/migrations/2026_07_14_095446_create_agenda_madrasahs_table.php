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
        Schema::create('agenda_madrasahs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');

            // Tipe agenda untuk menentukan perilaku di Jurnal Mengajar
            $table->enum('tipe_agenda', ['libur_nasional', 'kegiatan_bersama', 'kondisi_kelas']);

            $table->string('nama_agenda'); // Contoh: Pekan Madaris, Class Meeting

            // Hanya diisi jika agendanya khusus kelas tertentu (tipe: kondisi_kelas)
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->cascadeOnDelete();

            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_madrasahs');
    }
};
