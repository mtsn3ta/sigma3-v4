<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKegiatan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kegiatan';

    protected $fillable = [
        'proposal_kegiatan_id',
        'created_by_id',

        'uraian_pelaksanaan',
        'pencapaian',
        'prestasi',
        'evaluasi',
        'penutup',

        'dokumentasi',
        'sertifikat',
        'berita_acara',

        'status',

        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'dokumentasi' => 'array',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'pending_pembina' => 'Menunggu Pembina',
            'pending_waka' => 'Menunggu Waka Kesiswaan',
            'pending_kepala' => 'Menunggu Kepala Madrasah',
            'revision' => 'Perlu Revisi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => '-',
        };
    }
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function getTitleAttribute(): string
    {
        return $this->proposal?->nama_kegiatan ?? 'Laporan Kegiatan';
    }
    public function proposal()
    {
        return $this->belongsTo(
            ProposalKegiatan::class,
            'proposal_kegiatan_id'
        );
    }

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by_id'
        );
    }

    public function realisasiAnggaran()
    {
        return $this->hasMany(
            LaporanRealisasiAnggaran::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getTotalRealisasiAttribute(): float
    {
        if ($this->relationLoaded('realisasiAnggaran')) {
            return (float) $this->realisasiAnggaran->sum('subtotal');
        }

        return (float) $this->realisasiAnggaran()->sum('subtotal');
    }

    public function getSelisihAnggaranAttribute(): float
    {
        return (float) (
            $this->proposal?->total_anggaran ?? 0
        ) - $this->total_realisasi;
    }
    public function peserta()
    {
        return $this->hasMany(
            LaporanKegiatanPeserta::class
        );
    }

    public function pendamping()
    {
        return $this->hasMany(
            LaporanKegiatanPendamping::class
        );
    }
    public function approvalLogs()
    {
        return $this->hasMany(
            LaporanApprovalLog::class
        );
    }
    public function isLocked(): bool
    {
        return in_array($this->status, [
            'pending_pembina',
            'pending_waka',
            'pending_kepala',
            'approved',
        ]);
    }
    public function revisionLogs()
    {
        return $this->morphMany(
            RevisionLog::class,
            'revisionable'
        )->latest();
    }
}
