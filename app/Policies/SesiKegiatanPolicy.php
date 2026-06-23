<?php

namespace App\Policies;

use App\Models\SesiKegiatan;
use App\Models\User;

class SesiKegiatanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('sesi.view');
    }

    public function view(User $user, SesiKegiatan $sesiKegiatan): bool
    {
        return $user->can('sesi.view');
    }

    public function create(User $user): bool
    {
        return $user->can('sesi.create');
    }

    public function update(User $user, SesiKegiatan $sesiKegiatan): bool
    {
        return $user->can('sesi.edit');
    }

    public function delete(User $user, SesiKegiatan $sesiKegiatan): bool
    {
        return $user->can('sesi.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('sesi.delete');
    }

    public function restore(User $user, SesiKegiatan $sesiKegiatan): bool
    {
        return false;
    }

    public function forceDelete(User $user, SesiKegiatan $sesiKegiatan): bool
    {
        return false;
    }
}
