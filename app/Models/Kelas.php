<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'status',
        'qr_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (Kelas $kelas) {
            if (empty($kelas->qr_token)) {
                $kelas->qr_token = \Illuminate\Support\Str::random(32);
            }
        });
    }

    public function presensiGuruKelas()
    {
        return $this->hasMany(PresensiGuruKelas::class);
    }

    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }

    public function siswa()
    {
        return $this->belongsToMany(
            Siswa::class,
            'kelas_siswa',
            'kelas_id',
            'siswa_id',
        )
            ->where('siswa.status', 'aktif')
            ->wherePivot(
                'tahun_ajaran_id',
                TahunAjaran::query()
                    ->where('aktif', true)
                    ->value('id'),
            );
    }

    public function getNamaAttribute(): string
    {
        return $this->nama_kelas;
    }
}
