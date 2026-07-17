<?php

namespace App\Policies;

use App\Models\Galeri;
use App\Models\User;

class GaleriPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('galeri.view');
    }

    public function view(User $user, Galeri $galeri): bool
    {
        return $user->can('galeri.view');
    }

    public function create(User $user): bool
    {
        return $user->can('galeri.create');
    }

    public function update(User $user, Galeri $galeri): bool
    {
        return $user->can('galeri.edit');
    }

    public function delete(User $user, Galeri $galeri): bool
    {
        return $user->can('galeri.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('galeri.delete');
    }

    public function restore(User $user, Galeri $galeri): bool
    {
        return false;
    }

    public function forceDelete(User $user, Galeri $galeri): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, Galeri $galeri): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return $user->can('galeri.edit');
    }
}
