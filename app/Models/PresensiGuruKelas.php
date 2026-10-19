<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiGuruKelas extends Model
{
    use HasFactory;

    protected $table = 'presensi_guru_kelas';

    protected $fillable = [
        'tanggal',
        'jam_ke',
        'kelas_id',
        'pegawai_id',
        'jadwal_pelajaran_id',
        'metode',
        'waktu_masuk',
        'is_guru_pengganti',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_masuk' => 'datetime',
        'is_guru_pengganti' => 'boolean',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }
}
