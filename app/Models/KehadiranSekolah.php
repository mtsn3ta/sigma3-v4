<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranSekolah extends Model
{
    use HasFactory;

    protected $table = 'kehadiran_sekolah';

    protected $fillable = [

        'siswa_id',

        'tanggal',

        'status',

        'sumber',

        'jam_datang',

        'jam_pulang',

        'keterangan',

        'petugas_id',

    ];

    protected $casts = [

        'tanggal' => 'date',

    ];

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

    public function petugas()
    {
        return $this->belongsTo(
            Pegawai::class,
            'petugas_id'
        );
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

    public function isTerlambat(): bool
    {
        return $this->status === 'terlambat';
    }

    public function isIzin(): bool
    {
        return $this->status === 'izin';
    }

    public function isSakit(): bool
    {
        return $this->status === 'sakit';
    }

    public function isAlfa(): bool
    {
        return $this->status === 'alfa';
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

            'hadir'      => 'Hadir',

            'terlambat'  => 'Terlambat',

            'izin'       => 'Izin',

            'sakit'      => 'Sakit',

            'alfa'       => 'Alfa',

            default      => '-',
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
