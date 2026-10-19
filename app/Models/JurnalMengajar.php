<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class JurnalMengajar extends Model
{
    use HasFactory;

    protected $table = 'jurnal_mengajar';

    protected $fillable = [

        'pegawai_id',

        'kelas_id',

        'tanggal',

        'jam_ke',

        'mapel',

        'materi',

        'catatan',

        'dokumentasi_foto',

        'dokumentasi_video',

    ];

    protected $casts = [

        'tanggal' => 'date',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(
            Pegawai::class
        );
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(
            Kelas::class
        );
    }

    public function kehadiranPembelajaran(): HasMany
    {
        return $this->hasMany(
            KehadiranPembelajaran::class
        );
    }

    /**
     * Catatan / umpan balik dari Kepala Madrasah atas jurnal ini.
     */
    public function umpanBalik(): MorphMany
    {
        return $this->morphMany(UmpanBalikGuru::class, 'referensi');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getHariAttribute(): string
    {
        return $this->tanggal
            ->translatedFormat('l');
    }

    public function isToday(): bool
    {
        return $this->tanggal->isToday();
    }
}
