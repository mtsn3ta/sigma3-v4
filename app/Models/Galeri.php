<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';

    protected $fillable = [

        'ekstrakurikuler_id',

        'judul',

        'deskripsi',

        'tanggal',

        'kategori',

        'created_by_id',

    ];

    protected $casts = [

        'tanggal' => 'date',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function ekstrakurikuler()
    {
        return $this->belongsTo(
            Ekstrakurikuler::class
        );
    }

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by_id'
        );
    }
    public function foto()
    {
        return $this->hasMany(
            GaleriFoto::class
        )->orderBy('urutan');
    }
    public function getCoverAttribute(): ?string
    {
        return $this->foto()
            ->orderBy('urutan')
            ->value('foto');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {

            'sekolah' => 'Sekolah',

            'ekstrakurikuler' => 'Ekstrakurikuler',

            'prestasi' => 'Prestasi',

            'kegiatan' => 'Kegiatan',

            default => '-',
        };
    }
}
