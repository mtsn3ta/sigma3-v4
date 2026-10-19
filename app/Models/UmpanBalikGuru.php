<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UmpanBalikGuru extends Model
{
    use HasFactory;

    protected $table = 'umpan_balik_guru';

    protected $fillable = [
        'pegawai_id',
        'user_id',
        'kategori',
        'referensi_type',
        'referensi_id',
        'catatan',
        'dibaca_pada',
    ];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    /**
     * User (Kepala Madrasah / pemberi umpan balik).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke objek yang dikomentari (JurnalMengajar, PerangkatPembelajaran, DaftarNilai).
     */
    public function referensi(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeBelumDibaca(Builder $query): Builder
    {
        return $query->whereNull('dibaca_pada');
    }

    public function sudahDibaca(): bool
    {
        return $this->dibaca_pada !== null;
    }

    public function tandaiDibaca(): void
    {
        if ($this->dibaca_pada === null) {
            $this->forceFill(['dibaca_pada' => now()])->save();
        }
    }

    /**
     * Label kategori untuk tampilan.
     */
    public function labelKategori(): string
    {
        return match ($this->kategori) {
            'perangkat' => 'Perangkat Pembelajaran',
            'jurnal'    => 'Jurnal Mengajar',
            'nilai'     => 'Daftar Nilai',
            'kbm'       => 'Dokumentasi KBM',
            default     => 'Catatan Umum',
        };
    }

    public function labelReferensi(): ?string
    {
        $referensi = $this->referensi;

        return match (true) {
            $referensi instanceof PerangkatPembelajaran => $referensi->judul,
            $referensi instanceof JurnalMengajar         => $referensi->mapel . ' — ' . $referensi->tanggal?->translatedFormat('d M Y'),
            $referensi instanceof DaftarNilai            => $referensi->judul,
            default                                      => null,
        };
    }
}
