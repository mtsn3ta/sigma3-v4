<?php

namespace App\Policies;

use App\Models\Pengumuman;
use App\Models\User;

class PengumumanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('pengumuman.view');
    }

    public function view(User $user, Pengumuman $pengumuman): bool
    {
        return $user->can('pengumuman.view');
    }

    public function create(User $user): bool
    {
        return $user->can('pengumuman.create');
    }

    public function update(User $user, Pengumuman $pengumuman): bool
    {
        return $user->can('pengumuman.edit');
    }

    public function delete(User $user, Pengumuman $pengumuman): bool
    {
        return $user->can('pengumuman.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('pengumuman.delete');
    }

    public function restore(User $user, Pengumuman $pengumuman): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, Pengumuman $pengumuman): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Pengumuman $pengumuman): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return false;
    }
}
