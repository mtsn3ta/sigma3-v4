<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EkstrakurikulerPengampu;
use App\Models\User;

class ProposalKegiatan extends Model
{
    protected $table = 'proposal_kegiatan';

    protected $fillable = [
        'ekstrakurikuler_id',

        'nama_kegiatan',
        'jenis_kegiatan',

        'tempat',

        'tanggal_mulai',
        'tanggal_selesai',

        'latar_belakang',
        'dasar_kegiatan',
        'nama_penyelenggara',

        'tingkat_kegiatan',

        'nomor_surat_undangan',

        'tanggal_surat_undangan',

        'surat_undangan',

        'brosur',

        'tujuan',

        'penutup',

        'status',

        'submitted_at',
        'approved_at',

        'created_by_id',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'tanggal_surat_undangan' => 'date',
    ];
    protected $with = [
        'ekstrakurikuler',
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
    public function getJenisKegiatanLabelAttribute(): string
    {
        return match ($this->jenis_kegiatan) {
            'lomba' => 'Lomba',
            'festival' => 'Festival',
            'kemah' => 'Kemah',
            'pelatihan' => 'Pelatihan',
            'seminar' => 'Seminar',
            'workshop' => 'Workshop',
            'studi_banding' => 'Studi Banding',
            'kunjungan' => 'Kunjungan',
            'bakti_sosial' => 'Bakti Sosial',
            'lainnya' => 'Lainnya',
            default => '-',
        };
    }
    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by_id'
        );
    }

    public function peserta()
    {
        return $this->hasMany(
            ProposalPeserta::class
        );
    }

    public function rabs()
    {
        return $this->hasMany(ProposalRab::class);
    }

    public function getTotalAnggaranAttribute(): float
    {
        if ($this->relationLoaded('rabs')) {
            return (float) $this->rabs->sum('subtotal');
        }

        return (float) $this->rabs()->sum('subtotal');
    }

    public function approvalLogs()
    {
        return $this->hasMany(
            ProposalApprovalLog::class,
            'proposal_kegiatan_id'
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
    public function pelatih()
    {
        return EkstrakurikulerPengampu::query()
            ->with('pegawai')
            ->where('ekstrakurikuler_id', $this->ekstrakurikuler_id)
            ->where('role', 'pelatih')
            ->first();
    }

    public function pembina()
    {
        return EkstrakurikulerPengampu::query()
            ->with('pegawai')
            ->where('ekstrakurikuler_id', $this->ekstrakurikuler_id)
            ->where('role', 'pembina')
            ->first();
    }
    public function wakaKesiswaan()
    {
        return User::role('waka_kesiswaan')->first();
    }
    public function kepalaMadrasah()
    {
        return User::role('kepala_madrasah')->first();
    }

    public function isPelatihMerangkapPembina(): bool
    {
        $pelatih = $this->pelatih();
        $pembina = $this->pembina();

        return
            $pelatih &&
            $pembina &&
            $pelatih->pegawai_id === $pembina->pegawai_id;
    }
    public function revisionLogs()
    {
        return $this->morphMany(
            RevisionLog::class,
            'revisionable'
        )->latest();
    }
    public function laporanKegiatan()
    {
        return $this->hasOne(
            LaporanKegiatan::class
        );
    }

    public function hasLaporan(): bool
    {
        return $this->laporanKegiatan()->exists();
    }
    public function pendamping()
    {
        return $this->hasMany(
            ProposalPendamping::class
        );
    }
}
