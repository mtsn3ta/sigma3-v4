<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanKegiatanPeserta extends Model
{
    use HasFactory;

    protected $table = 'laporan_kegiatan_peserta';

    protected $fillable = [

        'laporan_kegiatan_id',

        'proposal_peserta_id',

        'siswa_id',

        'status',

        'keterangan',

    ];

    public function laporan()
    {
        return $this->belongsTo(
            LaporanKegiatan::class,
            'laporan_kegiatan_id'
        );
    }

    public function proposalPeserta()
    {
        return $this->belongsTo(
            ProposalPeserta::class,
            'proposal_peserta_id'
        );
    }

    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class
        );
    }
}
