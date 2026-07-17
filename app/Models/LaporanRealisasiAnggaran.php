<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanRealisasiAnggaran extends Model
{
    use HasFactory;

    protected $table = 'laporan_realisasi_anggaran';

    protected $fillable = [

        'laporan_kegiatan_id',
        'proposal_rab_id',
        'uraian',
        'qty',
        'satuan',

        'harga_satuan',
        'subtotal',

        'bukti',

    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function ($item) {

            $item->subtotal =
                ($item->qty ?? 0)
                * ($item->harga_satuan ?? 0);
        });
    }

    public function laporan()
    {
        return $this->belongsTo(
            LaporanKegiatan::class,
            'laporan_kegiatan_id'
        );
    }
    public function proposalRab()
    {
        return $this->belongsTo(
            ProposalRab::class,
            'proposal_rab_id'
        );
    }
}
