<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';

    protected $fillable = [

        'ekstrakurikuler_id',

        'nama_prestasi',

        'nama_lomba',

        'jenis',

        'tingkat',

        'tanggal',

        'keterangan',

        'foto',

        'created_by_id',

    ];

    protected $casts = [

        'tanggal' => 'date',

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

    public function siswa()
    {
        return $this->belongsToMany(

            Siswa::class,

            'prestasi_siswa'

        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {

            'individu' => 'Individu',

            'tim' => 'Tim',

            default => '-',
        };
    }
}
