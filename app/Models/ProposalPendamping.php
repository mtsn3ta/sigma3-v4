<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProposalPendamping extends Model
{
    use HasFactory;

    protected $table = 'proposal_pendamping';

    protected $fillable = [
        'proposal_kegiatan_id',
        'pegawai_id',
        'peran',
    ];

    public function proposal()
    {
        return $this->belongsTo(
            ProposalKegiatan::class,
            'proposal_kegiatan_id'
        );
    }

    public function pegawai()
    {
        return $this->belongsTo(
            Pegawai::class
        );
    }
}
