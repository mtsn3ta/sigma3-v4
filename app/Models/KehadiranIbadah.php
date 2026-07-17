<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranIbadah extends Model
{
    use HasFactory;

    protected $table = 'kehadiran_ibadah';

    protected $fillable = [

        'siswa_id',

        'tanggal',

        'jenis',

        'status',

        'sumber',

        'petugas_id',

        'waktu_scan',

    ];

    protected $casts = [

        'tanggal'    => 'date',

        'waktu_scan' => 'datetime:H:i',

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

    public function isDhuha(): bool
    {
        return $this->jenis === 'dhuha';
    }

    public function isDzuhur(): bool
    {
        return $this->jenis === 'dzuhur';
    }

    public function isHadir(): bool
    {
        return $this->status === 'hadir';
    }

    public function isTidakHadir(): bool
    {
        return $this->status === 'tidak_hadir';
    }

    public function isMenstruasi(): bool
    {
        return $this->status === 'menstruasi';
    }

    public function isRfid(): bool
    {
        return $this->sumber === 'rfid';
    }

    public function isManual(): bool
    {
        return $this->sumber === 'manual';
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {

            'dhuha'  => 'Dhuha',

            'dzuhur' => 'Dzuhur',

            default  => '-',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {

            'hadir'         => 'Hadir',

            'tidak_hadir'   => 'Tidak Hadir',

            'menstruasi'    => 'Menstruasi',

            default         => '-',
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
