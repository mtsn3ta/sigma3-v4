<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalRab extends Model
{
    protected $table = 'proposal_rab';

    protected $fillable = [
        'proposal_kegiatan_id',

        'uraian',
        'qty',
        'satuan',

        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function ($rab) {

            $rab->subtotal =
                ($rab->qty ?? 0)
                * ($rab->harga_satuan ?? 0);
        });
    }

    public function proposal()
    {
        return $this->belongsTo(
            ProposalKegiatan::class,
            'proposal_kegiatan_id'
        );
    }
    public function realisasi()
    {
        return $this->hasMany(
            LaporanRealisasiAnggaran::class,
            'proposal_rab_id'
        );
    }
}
