<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];
    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }
    public function anggotaEkskul()
    {
        return $this->hasMany(AnggotaEkskul::class);
    }
    public static function aktifId(): ?int
    {
        return static::query()

            ->where('aktif', true)

            ->value('id');
    }
}
