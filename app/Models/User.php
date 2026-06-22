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
}
