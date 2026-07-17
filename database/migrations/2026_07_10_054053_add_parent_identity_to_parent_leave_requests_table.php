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
        Schema::table('parent_leave_requests', function (Blueprint $table) {

            $table->string('nama_pengirim')
                ->after('keterangan');

            $table->enum('hubungan', [
                'ayah',
                'ibu',
                'wali',
            ])->after('nama_pengirim');
        });
    }

    public function down(): void
    {
        Schema::table('parent_leave_requests', function (Blueprint $table) {

            $table->dropColumn([
                'nama_pengirim',
                'hubungan',
            ]);
        });
    }
};
