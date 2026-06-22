<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;

class SiswaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('siswa.view');
    }

    public function view(User $user, Siswa $siswa): bool
    {
        return $user->can('siswa.view');
    }

    public function create(User $user): bool
    {
        return $user->can('siswa.manage');
    }

    public function update(User $user, Siswa $siswa): bool
    {
        return $user->can('siswa.manage');
    }

    public function delete(User $user, Siswa $siswa): bool
    {
        return $user->can('siswa.manage');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('siswa.manage');
    }
}
