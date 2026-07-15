<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPelajaran extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pelajarans';

    protected $fillable = [
        'tahun_ajaran_id',
        'hari',
        'jam_ke',
        'kelas_id',
        'ploting_guru_id', // Menghubungkan ke kotak puzzle ploting
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Melalui relasi ini, kita bisa tahu siapa gurunya dan apa mapelnya
    public function plotingGuru(): BelongsTo
    {
        return $this->belongsTo(PlotingGuru::class, 'ploting_guru_id');
    }
}
