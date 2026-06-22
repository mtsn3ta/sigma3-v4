<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nama', 150);

            $table->string('nik_nip', 50)
                ->unique();
            // Internal = Guru/Waka/BK/Piket/Pembina
            // Eksternal = Pelatih Eksternal
            $table->enum('jenis_pegawai', [
                'internal',
                'eksternal'
            ]);

            $table->string('no_hp', 30);

            $table->string('rfid_uid', 100)
                ->nullable()
                ->unique();

            $table->enum('jenis_kelamin', [
                'L',
                'P'
            ]);

            $table->string('tempat_lahir', 100);

            $table->date('tanggal_lahir');

            $table->text('alamat');

            $table->string('foto')
                ->nullable();

            $table->string('ttd_digital')
                ->nullable();

            $table->enum('status', [
                'aktif',
                'nonaktif'
            ])->default('aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
