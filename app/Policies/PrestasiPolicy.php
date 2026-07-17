<?php

namespace App\Policies;

use App\Models\Prestasi;
use App\Models\User;

class PrestasiPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('prestasi.view');
    }

    public function view(User $user, Prestasi $prestasi): bool
    {
        return $user->can('prestasi.view');
    }

    public function create(User $user): bool
    {
        return $user->can('prestasi.create');
    }

    public function update(User $user, Prestasi $prestasi): bool
    {
        return $user->can('prestasi.edit');
    }

    public function delete(User $user, Prestasi $prestasi): bool
    {
        return $user->can('prestasi.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('prestasi.delete');
    }

    public function restore(User $user, Prestasi $prestasi): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, Prestasi $prestasi): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Prestasi $prestasi): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return false;
    }
}
