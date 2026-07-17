<?php

namespace App\Policies;

use App\Models\Pegawai;
use App\Models\User;

class PegawaiPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('pegawai.view');
    }

    public function view(User $user, Pegawai $pegawai): bool
    {
        return $user->can('pegawai.view');
    }

    public function create(User $user): bool
    {
        return $user->can('pegawai.manage');
    }

    public function update(User $user, Pegawai $pegawai): bool
    {
        return $user->can('pegawai.manage');
    }

    public function delete(User $user, Pegawai $pegawai): bool
    {
        return $user->can('pegawai.manage');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('pegawai.manage');
    }

    public function restore(User $user, Pegawai $pegawai): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, Pegawai $pegawai): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
