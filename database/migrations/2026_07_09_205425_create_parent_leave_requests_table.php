<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_leave_requests', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->enum('jenis', [
                'sakit',
                'izin',
            ]);

            $table->text('keterangan');

            $table->boolean('has_doctor_letter')
                ->default(false);

            $table->string('doctor_letter')
                ->nullable();

            $table->string('parent_selfie');

            $table->longText('parent_signature');

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('pegawai')
                ->nullOnDelete();

            $table->timestamp('verified_at')
                ->nullable();

            $table->text('verification_note')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_leave_requests');
    }
};
