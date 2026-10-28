<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaMadrasah extends Model
{
    use HasFactory;

    protected $table = 'agenda_madrasahs';

    protected $fillable = [
        'tanggal',
        'tanggal_mulai',
        'tanggal_selesai',
        'tipe_agenda',
        'nama_agenda',
        'kelas_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if ($model->tanggal_mulai && !$model->tanggal) {
                $model->tanggal = $model->tanggal_mulai;
            }
            if ($model->tanggal && !$model->tanggal_mulai) {
                $model->tanggal_mulai = $model->tanggal;
            }
            if (empty($model->tanggal_selesai)) {
                $model->tanggal_selesai = $model->tanggal_mulai;
            }
        });
    }

    /**
     * Scope untuk mengecek agenda yang aktif pada suatu tanggal (mendukung single date maupun range)
     */
    public function scopeAktifPada(Builder $query, CarbonInterface|string $date): Builder
    {
        $dateStr = $date instanceof CarbonInterface ? $date->toDateString() : (string) $date;

        return $query->where(function (Builder $q) use ($dateStr) {
            $q->where(function (Builder $sub) use ($dateStr) {
                $sub->whereNotNull('tanggal_mulai')
                    ->where('tanggal_mulai', '<=', $dateStr)
                    ->where(function (Builder $end) use ($dateStr) {
                        $end->whereNull('tanggal_selesai')
                            ->orWhere('tanggal_selesai', '>=', $dateStr);
                    });
            })->orWhere(function (Builder $sub) use ($dateStr) {
                $sub->whereNull('tanggal_mulai')
                    ->whereDate('tanggal', $dateStr);
            });
        });
    }

    /**
     * Teks format tampilan periode tanggal (Contoh: "26 Okt 2026 – 30 Okt 2026" atau "17 Agu 2026")
     */
    public function getPeriodeTeksAttribute(): string
    {
        $mulai = $this->tanggal_mulai ?? $this->tanggal;
        $selesai = $this->tanggal_selesai ?? $mulai;

        if (!$mulai) {
            return '-';
        }

        if (!$selesai || $mulai->equalTo($selesai)) {
            return $mulai->translatedFormat('d M Y');
        }

        if ($mulai->format('M Y') === $selesai->format('M Y')) {
            return $mulai->translatedFormat('d') . ' – ' . $selesai->translatedFormat('d M Y');
        }

        if ($mulai->format('Y') === $selesai->format('Y')) {
            return $mulai->translatedFormat('d M') . ' – ' . $selesai->translatedFormat('d M Y');
        }

        return $mulai->translatedFormat('d M Y') . ' – ' . $selesai->translatedFormat('d M Y');
    }

    /**
     * Menghitung total hari durasi acara
     */
    public function getDurasiHariAttribute(): int
    {
        $mulai = $this->tanggal_mulai ?? $this->tanggal;
        $selesai = $this->tanggal_selesai ?? $mulai;

        if (!$mulai) {
            return 1;
        }

        if (!$selesai || $mulai->equalTo($selesai)) {
            return 1;
        }

        return (int) $mulai->diffInDays($selesai) + 1;
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
