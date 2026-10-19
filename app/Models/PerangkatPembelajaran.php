<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PerangkatPembelajaran extends Model
{
    use HasFactory;

    protected $table = 'perangkat_pembelajaran';

    protected $fillable = [
        'pegawai_id',
        'tahun_ajaran_id',
        'kelas_id',
        'mapel',
        'judul',
        'jenis',
        'file_path',
        'deskripsi',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function umpanBalik(): MorphMany
    {
        return $this->morphMany(UmpanBalikGuru::class, 'referensi');
    }
}
