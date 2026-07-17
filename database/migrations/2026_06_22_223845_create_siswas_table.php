<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('nisn', 30)
                ->unique();

            $table->string('nama', 150);

            $table->enum('jenis_kelamin', [
                'L',
                'P',
            ]);

            $table->string('tempat_lahir', 100);

            $table->date('tanggal_lahir');

            $table->text('alamat');

            $table->string('no_hp', 30)
                ->nullable();

            $table->string('foto')
                ->nullable();

            $table->string('nama_ayah', 150)
                ->nullable();

            $table->string('nama_ibu', 150)
                ->nullable();

            $table->string('pekerjaan_ayah', 150)
                ->nullable();

            $table->string('pekerjaan_ibu', 150)
                ->nullable();

            $table->string('nik_ayah', 50)
                ->nullable();

            $table->string('nik_ibu', 50)
                ->nullable();

            $table->text('alamat_ortu')
                ->nullable();

            $table->string('telpon_ortu', 30)
                ->nullable();

            $table->string('nama_wali', 150)
                ->nullable();

            $table->string('pekerjaan_wali', 150)
                ->nullable();

            $table->text('alamat_wali')
                ->nullable();

            $table->string('telpon_wali', 30)
                ->nullable();

            $table->integer('anak_ke')
                ->nullable();

            $table->string('sekolah_asal', 150)
                ->nullable();

            $table->string('rfid_uid', 100)
                ->nullable()
                ->unique();

            $table->enum('status', [
                'aktif',
                'mutasi',
                'lulus',
            ])->default('aktif');

            $table->timestamps();

            $table->index('user_id');
            $table->index('nisn');
            $table->index('rfid_uid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
