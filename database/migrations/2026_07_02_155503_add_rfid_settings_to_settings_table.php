<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | RFID School
            |--------------------------------------------------------------------------
            */

            $table->boolean('rfid_school_enabled')
                ->default(true)
                ->after('tahun_ajaran_aktif_id');

            $table->time('rfid_school_checkin_start')
                ->default('06:00:00')
                ->after('rfid_school_enabled');

            $table->time('rfid_school_checkin_end')
                ->default('07:10:00')
                ->after('rfid_school_checkin_start');

            $table->time('rfid_school_checkout_start')
                ->default('14:30:00')
                ->after('rfid_school_checkin_end');

            $table->time('rfid_school_checkout_end')
                ->default('17:00:00')
                ->after('rfid_school_checkout_start');

            /*
            |--------------------------------------------------------------------------
            | RFID Ibadah
            |--------------------------------------------------------------------------
            */

            $table->boolean('rfid_ibadah_enabled')
                ->default(true)
                ->after('rfid_school_checkout_end');

            $table->time('rfid_ibadah_dhuha_start')
                ->default('06:40:00')
                ->after('rfid_ibadah_enabled');

            $table->time('rfid_ibadah_dhuha_end')
                ->default('07:00:00')
                ->after('rfid_ibadah_dhuha_start');

            $table->time('rfid_ibadah_dzuhur_start')
                ->default('11:45:00')
                ->after('rfid_ibadah_dhuha_end');

            $table->time('rfid_ibadah_dzuhur_end')
                ->default('12:20:00')
                ->after('rfid_ibadah_dzuhur_start');

            $table->boolean('rfid_ibadah_disable_friday')
                ->default(true)
                ->after('rfid_ibadah_dzuhur_end');

            /*
            |--------------------------------------------------------------------------
            | RFID General
            |--------------------------------------------------------------------------
            */

            $table->unsignedTinyInteger('rfid_general_uid_lock_seconds')
                ->default(2)
                ->after('rfid_ibadah_disable_friday');

            $table->boolean('rfid_general_enable_web_nfc')
                ->default(true)
                ->after('rfid_general_uid_lock_seconds');

            $table->boolean('rfid_general_enable_usb_reader')
                ->default(true)
                ->after('rfid_general_enable_web_nfc');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->dropColumn([

                'rfid_school_enabled',

                'rfid_school_checkin_start',
                'rfid_school_checkin_end',

                'rfid_school_checkout_start',
                'rfid_school_checkout_end',

                'rfid_ibadah_enabled',

                'rfid_ibadah_dhuha_start',
                'rfid_ibadah_dhuha_end',

                'rfid_ibadah_dzuhur_start',
                'rfid_ibadah_dzuhur_end',

                'rfid_ibadah_disable_friday',

                'rfid_general_uid_lock_seconds',

                'rfid_general_enable_web_nfc',

                'rfid_general_enable_usb_reader',

            ]);
        });
    }
};
