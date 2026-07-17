<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalPeserta extends Model
{
    protected $table = 'proposal_peserta';

    protected $fillable = [
        'proposal_kegiatan_id',
        'siswa_id',
    ];

    public function proposal()
    {
        return $this->belongsTo(
            ProposalKegiatan::class,
            'proposal_kegiatan_id'
        );
    }
    public function kelasAktif()
    {
        return $this->hasOneThrough(
            KelasSiswa::class,
            Siswa::class,
            'id',
            'siswa_id',
            'siswa_id',
            'id'
        );
    }
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
