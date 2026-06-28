<?php

namespace App\Policies;

use App\Models\IzinKeluar;
use App\Models\User;

class IzinKeluarPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            'super_admin',
            'bk',
            'piket',
        ]);
    }

    public function view(User $user, IzinKeluar $izinKeluar): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, IzinKeluar $izinKeluar): bool
    {
        return $this->viewAny($user);
    }

    public function delete(User $user, IzinKeluar $izinKeluar): bool
    {
        return $user->hasRole('super_admin');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }
}
