<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasGuruIzin extends Model
{
    use HasFactory;

    protected $table = 'tugas_guru_izin';

    protected $fillable = [

        'pegawai_id',

        'petugas_id',

        'kelas_id',

        'tanggal',

        'mapel',

        'jam_ke',

        'isi_tugas',

        'status',

    ];

    protected $casts = [

        'tanggal' => 'date',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function guru()
    {
        return $this->belongsTo(
            Pegawai::class,
            'pegawai_id'
        );
    }

    public function petugas()
    {
        return $this->belongsTo(
            Pegawai::class,
            'petugas_id'
        );
    }

    public function kelas()
    {
        return $this->belongsTo(
            Kelas::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isBaru(): bool
    {
        return $this->status === 'baru';
    }

    public function isDisampaikan(): bool
    {
        return $this->status === 'disampaikan';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {

            'baru' => 'Baru',

            'disampaikan' => 'Sudah Disampaikan',

            'selesai' => 'Selesai',

            default => '-',
        };
    }

    public function getHariAttribute(): string
    {
        return $this->tanggal
            ->translatedFormat('l');
    }
}
