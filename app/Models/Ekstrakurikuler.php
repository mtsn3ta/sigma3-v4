<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekstrakurikuler extends Model
{
    use HasFactory;

    protected $table = 'ekstrakurikuler';

    protected $fillable = [
        'nama',
        'deskripsi',
        'jadwal',
        'kuota',
        'status',
    ];
    protected $casts = [
        'jadwal' => 'array',
    ];

    public function pengampu()
    {
        return $this->hasMany(EkstrakurikulerPengampu::class);
    }

    public function anggotaEkskul()
    {
        return $this->hasMany(AnggotaEkskul::class);
    }

    public function sesiKegiatan()
    {
        return $this->hasMany(SesiKegiatan::class);
    }

    public function laporanBulanan()
    {
        return $this->hasMany(LaporanBulanan::class);
    }

    public function proposalKegiatan()
    {
        return $this->hasMany(ProposalKegiatan::class);
    }
}
