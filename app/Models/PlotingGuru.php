<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlotingGuru extends Model
{
    use HasFactory;

    protected $table = 'ploting_gurus';

    protected $fillable = [
        'tahun_ajaran_id',
        'pegawai_id',
        'kode_guru',
        'mapel',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function jadwalPelajarans(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class, 'ploting_guru_id');
    }
}
