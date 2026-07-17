<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanKegiatanPendamping extends Model
{
    use HasFactory;

    protected $table = 'laporan_kegiatan_pendamping';

    protected $fillable = [

        'laporan_kegiatan_id',

        'proposal_pendamping_id',

        'pegawai_id',

        'peran',

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

    public function proposalPendamping()
    {
        return $this->belongsTo(
            ProposalPendamping::class,
            'proposal_pendamping_id'
        );
    }

    public function pegawai()
    {
        return $this->belongsTo(
            Pegawai::class
        );
    }
}
