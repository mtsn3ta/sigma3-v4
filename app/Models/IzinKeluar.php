<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinKeluar extends Model
{
    use HasFactory;

    protected $table = 'izin_keluar';

    protected $fillable = [
        'siswa_id',
        'pegawai_id',
        'tanggal',
        'tanggal_selesai', // <--- TAMBAHAN BARU
        'jam_keluar',
        'jam_kembali',
        'waktu_cetak',
        'tujuan',
        'keperluan',
        'status_proses',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'tanggal_selesai' => 'date', // <--- TAMBAHAN BARU
            'waktu_cetak' => 'datetime',
        ];
    }
    public function getJamKeluarFormattedAttribute(): ?string
    {
        if (! $this->jam_keluar) {
            return null;
        }

        return substr((string) $this->jam_keluar, 0, 5);
    }

    public function getJamKembaliFormattedAttribute(): ?string
    {
        if (! $this->jam_kembali) {
            return null;
        }

        return substr((string) $this->jam_kembali, 0, 5);
    }
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class
        );
    }

    public function pegawai()
    {
        return $this->belongsTo(
            Pegawai::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_proses) {

            'keluar' => 'Keluar',

            'kembali' => 'Kembali',

            'pulang' => 'Pulang',

            default => '-',
        };
    }

    public function isKeluar(): bool
    {
        return $this->status_proses === 'keluar';
    }

    public function isKembali(): bool
    {
        return $this->status_proses === 'kembali';
    }

    public function isPulang(): bool
    {
        return $this->status_proses === 'pulang';
    }
}
