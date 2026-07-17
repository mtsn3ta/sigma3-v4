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
        // Kalau punya relasi siswa → tampilkan nama siswa
        if ($this->siswa) {
            return $this->siswa->nama;
        }

        // Kalau punya relasi pegawai → tampilkan nama pegawai
        if ($this->pegawai) {
            return $this->pegawai->nama;
        }

        // Fallback ke username kalau tidak ada relasi
        return $this->username;
    }
    public function canAccessPanel(Panel $panel): bool
    {
        // Gerbang pertama: akun harus aktif, apapun panelnya
        if ($this->status !== 'aktif') {
            return false;
        }

        // Panel siswa: hanya role 'siswa' yang boleh masuk
        if ($panel->getId() === 'siswa') {
            return $this->hasRole('siswa');
        }

        // Panel admin: semua role staff, TAPI bukan siswa
        if ($panel->getId() === 'admin') {
            return $this->hasAnyRole([
                'super_admin',
                'kepala_madrasah',
                'guru',
                'waka_kesiswaan',
                'waka_kurikulum',
                'waka_humas',
                'waka_sarpras',
                'bk',
                'piket',
                'pembina',
                'pelatih',
            ]);
        }

        return false;
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
