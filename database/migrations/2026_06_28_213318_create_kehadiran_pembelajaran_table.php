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
        Schema::create('kehadiran_pembelajaran', function (Blueprint $table) {

            $table->id();

            $table->foreignId('jurnal_mengajar_id')
                ->constrained('jurnal_mengajar')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('status', 50)
                ->comment('bolos, ke_bk, ke_uks, lomba, dispensasi, lainnya');

            $table->text('keterangan')
                ->nullable()
                ->comment('Keterangan tambahan jika diperlukan');

            $table->timestamps();

            $table->unique(
                [
                    'jurnal_mengajar_id',
                    'siswa_id',
                ],
                'kehadiran_pembelajaran_jurnal_siswa_unique'
            );

            $table->index('status');
            $table->index('siswa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_pembelajaran');
    }
};
