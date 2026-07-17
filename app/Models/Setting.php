<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [

        'nama_madrasah',

        'alamat',

        'telepon',

        'email',

        'logo',
        'logo_resmi',
        'favicon',
        'kepala_madrasah_photo',

        'kepala_aktif_id',

        'tahun_ajaran_aktif_id',
        /*
|--------------------------------------------------------------------------
| RFID School
|--------------------------------------------------------------------------
*/

        'rfid_school_enabled',

        'rfid_school_checkin_start',

        'rfid_school_checkin_end',

        'rfid_school_checkout_start',

        'rfid_school_checkout_end',

        /*
|--------------------------------------------------------------------------
| RFID Ibadah
|--------------------------------------------------------------------------
*/

        'rfid_ibadah_enabled',

        'rfid_ibadah_dhuha_start',

        'rfid_ibadah_dhuha_end',

        'rfid_ibadah_dzuhur_start',

        'rfid_ibadah_dzuhur_end',

        'rfid_ibadah_disable_friday',

        /*
|--------------------------------------------------------------------------
| RFID General
|--------------------------------------------------------------------------
*/

        'rfid_general_uid_lock_seconds',

        'rfid_general_enable_web_nfc',

        'rfid_general_enable_usb_reader',

    ];
    protected function casts(): array
    {
        return [

            /*
        |--------------------------------------------------------------------------
        | RFID School
        |--------------------------------------------------------------------------
        */

            'rfid_school_enabled' => 'boolean',

            /*
        |--------------------------------------------------------------------------
        | RFID Ibadah
        |--------------------------------------------------------------------------
        */

            'rfid_ibadah_enabled' => 'boolean',

            'rfid_ibadah_disable_friday' => 'boolean',

            /*
        |--------------------------------------------------------------------------
        | RFID General
        |--------------------------------------------------------------------------
        */

            'rfid_general_enable_web_nfc' => 'boolean',

            'rfid_general_enable_usb_reader' => 'boolean',

            'rfid_general_uid_lock_seconds' => 'integer',

        ];
    }
    public function kepalaAktif()
    {
        return $this->belongsTo(
            Pegawai::class,
            'kepala_aktif_id'
        );
    }

    public function tahunAjaranAktif()
    {
        return $this->belongsTo(
            TahunAjaran::class,
            'tahun_ajaran_aktif_id'
        );
    }
}
