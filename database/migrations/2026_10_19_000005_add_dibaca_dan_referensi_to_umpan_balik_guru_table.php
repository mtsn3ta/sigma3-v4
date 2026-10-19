<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('umpan_balik_guru', function (Blueprint $table) {
            if (!Schema::hasColumn('umpan_balik_guru', 'referensi_type')) {
                $table->string('referensi_type')
                    ->nullable()
                    ->after('kategori');
            }

            if (!Schema::hasColumn('umpan_balik_guru', 'dibaca_pada')) {
                $table->timestamp('dibaca_pada')
                    ->nullable()
                    ->after('catatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('umpan_balik_guru', function (Blueprint $table) {
            $table->dropColumn(['referensi_type', 'dibaca_pada']);
        });
    }
};
