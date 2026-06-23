<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranEkskul extends Model
{
    use HasFactory;

    protected $table = 'kehadiran_ekskul';

    protected $fillable = [
        'sesi_kegiatan_id',
        'siswa_id',
        'status',
        'sumber',
        'petugas_id',
        'waktu_scan',
    ];

    protected $casts = [
        'waktu_scan' => 'datetime',
    ];

    public function sesiKegiatan()
    {
        return $this->belongsTo(SesiKegiatan::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
