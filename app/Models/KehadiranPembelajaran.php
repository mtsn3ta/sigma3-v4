<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KehadiranPembelajaran extends Model
{
    use HasFactory;

    protected $table = 'kehadiran_pembelajaran';

    protected $fillable = [

        'jurnal_mengajar_id',

        'siswa_id',

        'status',

        'keterangan',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function jurnalMengajar(): BelongsTo
    {
        return $this->belongsTo(
            JurnalMengajar::class
        );
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(
            Siswa::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isBolos(): bool
    {
        return $this->status === 'bolos';
    }

    public function isKeBk(): bool
    {
        return $this->status === 'ke_bk';
    }

    public function isKeUks(): bool
    {
        return $this->status === 'ke_uks';
    }

    public function isDispensasi(): bool
    {
        return $this->status === 'dispensasi';
    }

    public function isLomba(): bool
    {
        return $this->status === 'lomba';
    }

    public function isLainnya(): bool
    {
        return $this->status === 'lainnya';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {

            'bolos'      => 'Bolos',

            'ke_bk'      => 'Ke BK',

            'ke_uks'     => 'Ke UKS',

            'dispensasi' => 'Dispensasi',

            'lomba'      => 'Lomba',

            'lainnya'    => 'Lainnya',

            default      => '-',
        };
    }
}
