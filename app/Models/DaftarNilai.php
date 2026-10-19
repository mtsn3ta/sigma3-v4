<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class DaftarNilai extends Model
{
    use HasFactory;

    protected $table = 'daftar_nilai';

    protected $fillable = [
        'pegawai_id',
        'tahun_ajaran_id',
        'kelas_id',
        'mapel',
        'judul',
        'jenis_nilai',
        'tanggal',
        'file_path',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

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

    public function nilaiSiswa(): HasMany
    {
        return $this->hasMany(NilaiSiswa::class);
    }

    public function umpanBalik(): MorphMany
    {
        return $this->morphMany(UmpanBalikGuru::class, 'referensi');
    }
}
