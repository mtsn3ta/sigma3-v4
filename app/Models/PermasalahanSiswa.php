<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermasalahanSiswa extends Model
{
    use HasFactory;

    protected $table = 'permasalahan_siswa';

    protected $fillable = [

        'siswa_id',

        'pegawai_id',

        'tanggal',

        'permasalahan',

        'penyelesaian',

        'poin',

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
