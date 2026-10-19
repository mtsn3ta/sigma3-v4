<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daftar_nilai', function (Blueprint $table) {
            $table->string('jenis_nilai', 50)->nullable()->after('judul');
            $table->date('tanggal')->nullable()->after('jenis_nilai');
        });

        Schema::create('nilai_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daftar_nilai_id')
                ->constrained('daftar_nilai')
                ->cascadeOnDelete();
            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['daftar_nilai_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_siswa');

        Schema::table('daftar_nilai', function (Blueprint $table) {
            $table->dropColumn(['jenis_nilai', 'tanggal']);
        });
    }
};
