<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranEkskul extends Model
{
    use HasFactory;

    protected $table = 'kehadiran_ekskul';

    protected $fillable = [
        'sesi_kegiatan_id',
        'siswa_id',
        'status',
        'sumber',
        'petugas_id',
        'waktu_scan',
    ];

    protected $casts = [
        'waktu_scan' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function sesiKegiatan()
    {
        return $this->belongsTo(SesiKegiatan::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isHadir(): bool
    {
        return $this->status === 'hadir';
    }

    public function isTidakHadir(): bool
    {
        return $this->status === 'tidak_hadir';
    }

    public function isRfid(): bool
    {
        return $this->sumber === 'rfid';
    }

    public function isManual(): bool
    {
        return $this->sumber === 'manual';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {

            'hadir'        => 'Hadir',

            'tidak_hadir'  => 'Tidak Hadir',

            default        => '-',
        };
    }

    public function getSumberLabelAttribute(): string
    {
        return match ($this->sumber) {

            'rfid'   => 'RFID',

            'manual' => 'Manual',

            default  => '-',
        };
    }
}
