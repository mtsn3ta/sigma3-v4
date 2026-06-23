<?php

namespace App\Policies;

use App\Models\AnggotaEkskul;
use App\Models\User;

class AnggotaEkskulPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('anggota.view');
    }

    public function view(User $user, AnggotaEkskul $anggotaEkskul): bool
    {
        return $user->can('anggota.view');
    }

    public function create(User $user): bool
    {
        return $user->can('anggota.create');
    }

    public function update(User $user, AnggotaEkskul $anggotaEkskul): bool
    {
        return $user->can('anggota.edit');
    }

    public function delete(User $user, AnggotaEkskul $anggotaEkskul): bool
    {
        return $user->can('anggota.nonaktif');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('anggota.nonaktif');
    }

    public function restore(User $user, AnggotaEkskul $anggotaEkskul): bool
    {
        return false;
    }

    public function forceDelete(User $user, AnggotaEkskul $anggotaEkskul): bool
    {
        return false;
    }
}
