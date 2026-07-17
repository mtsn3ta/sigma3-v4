<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanBulanan extends Model
{
    use HasFactory;

    protected $table = 'laporan_bulanan';

    protected $fillable = [

        'ekstrakurikuler_id',

        'bulan',
        'tahun',

        'created_by_id',

        // BAB I
        'pendahuluan',
        'tujuan',

        // BAB IV
        'evaluasi',
        'kendala',
        'solusi',

        // BAB V
        'penutup',

        // Lampiran
        'dokumentasi',

        // Workflow
        'status',

        'submitted_at',
        'approved_at',

    ];

    protected $casts = [

        'dokumentasi' => 'array',

        'submitted_at' => 'datetime',

        'approved_at' => 'datetime',

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

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

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

    public function getTitleAttribute(): string
    {
        return sprintf(
            'Laporan Bulanan %s %s',
            $this->bulan,
            $this->tahun
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

    /*
    |--------------------------------------------------------------------------
    | Approval Helpers
    |--------------------------------------------------------------------------
    */

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
        return User::role('waka_kesiswaan')
            ->with('pegawai')
            ->first();
    }

    public function kepalaMadrasah()
    {
        return User::role('kepala_madrasah')
            ->with('pegawai')
            ->first();
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

    /*
|--------------------------------------------------------------------------
| Logs
|--------------------------------------------------------------------------
*/

    public function approvalLogs()
    {
        return $this->hasMany(
            LaporanBulananApprovalLog::class
        );
    }

    public function revisionLogs()
    {
        return $this->morphMany(
            RevisionLog::class,
            'revisionable'
        )->latest();
    }
}
