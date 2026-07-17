<?php

namespace App\Models;

use App\Support\RoleHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EkstrakurikulerPengampu extends Model
{
    use HasFactory;

    protected $table = 'ekstrakurikuler_pengampu';

    protected $fillable = [
        'ekstrakurikuler_id',
        'pegawai_id',
        'role',
    ];

    protected static function booted(): void
    {
        static::deleted(function ($pengampu): void {

            $pegawai = $pengampu->pegawai;

            if (! $pegawai?->user) {
                return;
            }

            RoleHelper::removePengampuRoleIfUnused(
                $pegawai->user,
                $pegawai->id,
                $pengampu->role,
            );
        });
    }

    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
