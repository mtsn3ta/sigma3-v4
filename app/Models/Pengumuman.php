<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [

        'ekstrakurikuler_id',

        'judul',

        'isi',

        'lampiran',

        'tipe',

        'tanggal_mulai',

        'tanggal_selesai',

        'status',

        'is_pinned',

        'created_by_id',

    ];

    protected $casts = [

        'tanggal_mulai' => 'date',

        'tanggal_selesai' => 'date',

        'is_pinned' => 'boolean',

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

            'publish' => 'Publish',

            'arsip' => 'Arsip',

            default => '-',
        };
    }

    public function getTipeLabelAttribute(): string
    {
        return match ($this->tipe) {

            'info' => 'Info',

            'success' => 'Sukses',

            'warning' => 'Peringatan',

            'danger' => 'Darurat',

            default => '-',
        };
    }

    public function isActive(): bool
    {
        return
            $this->status === 'publish'
            && today()->between(
                $this->tanggal_mulai,
                $this->tanggal_selesai
            );
    }
}
