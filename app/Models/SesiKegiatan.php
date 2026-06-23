<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SesiKegiatan extends Model
{
    use HasFactory;

    protected $table = 'sesi_kegiatan';

    protected $fillable = [
        'ekstrakurikuler_id',
        'pelatih_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'status',
    ];

    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function kehadiran()
    {
        return $this->hasMany(KehadiranEkskul::class);
    }

    public function pelatih()
    {
        return $this->belongsTo(
            Pegawai::class,
            'pelatih_id'
        );
    }
}
