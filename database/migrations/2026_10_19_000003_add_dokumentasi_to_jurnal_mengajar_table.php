<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jurnal_mengajar', function (Blueprint $table) {
            if (!Schema::hasColumn('jurnal_mengajar', 'dokumentasi_foto')) {
                $table->string('dokumentasi_foto')->nullable()->after('catatan');
            }
            if (!Schema::hasColumn('jurnal_mengajar', 'dokumentasi_video')) {
                $table->string('dokumentasi_video')->nullable()->after('dokumentasi_foto');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jurnal_mengajar', function (Blueprint $table) {
            $table->dropColumn(['dokumentasi_foto', 'dokumentasi_video']);
        });
    }
};
