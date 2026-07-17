<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaEkskul extends Model
{
    use HasFactory;

    protected $table = 'anggota_ekskul';

    protected $fillable = [
        'siswa_id',
        'ekstrakurikuler_id',
        'tahun_ajaran_id',
        'tanggal_daftar',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
