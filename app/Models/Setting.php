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

        'kepala_aktif_id',

        'tahun_ajaran_aktif_id',

    ];

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
