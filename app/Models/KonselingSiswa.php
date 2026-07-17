<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonselingSiswa extends Model
{
    use HasFactory;

    protected $table = 'konseling_siswa';

    protected $fillable = [

        'siswa_id',

        'pegawai_id',

        'tanggal',

        'permasalahan',

        'hasil_konseling',

        'tindak_lanjut',

    ];

    protected $casts = [

        'tanggal' => 'date',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class
        );
    }

    public function pegawai()
    {
        return $this->belongsTo(
            Pegawai::class
        );
    }
}
