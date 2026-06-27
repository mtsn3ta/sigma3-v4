<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use App\Models\Siswa;
use App\Models\EkstrakurikulerPengampu;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory;
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        'username',
        'email',
        'password',
        'must_change_password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'must_change_password' => 'boolean',
            'password' => 'hashed',
        ];
    }
    public function getFilamentName(): string
    {
        return $this->username;
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->status === 'aktif';
    }
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class);
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }
    public function isPembinaEkskul(int $ekstrakurikulerId): bool
    {
        if (! $this->pegawai) {
            return false;
        }

        return EkstrakurikulerPengampu::query()
            ->where('pegawai_id', $this->pegawai->id)
            ->where('ekstrakurikuler_id', $ekstrakurikulerId)
            ->where('role', 'pembina')
            ->exists();
    }

    public function isPelatihEkskul(int $ekstrakurikulerId): bool
    {
        if (! $this->pegawai) {
            return false;
        }

        return EkstrakurikulerPengampu::query()
            ->where('pegawai_id', $this->pegawai->id)
            ->where('ekstrakurikuler_id', $ekstrakurikulerId)
            ->where('role', 'pelatih')
            ->exists();
    }

    public function managesEkstrakurikuler(int $ekstrakurikulerId): bool
    {
        return $this->isPembinaEkskul($ekstrakurikulerId)
            || $this->isPelatihEkskul($ekstrakurikulerId);
    }
    public function prestasi()
    {
        return $this->hasMany(

            Prestasi::class,

            'created_by_id'

        );
    }
    public function galeri()
    {
        return $this->hasMany(
            Galeri::class,
            'created_by_id'
        );
    }
    public function pengumuman()
    {
        return $this->hasMany(
            Pengumuman::class,
            'created_by_id'
        );
    }
}
