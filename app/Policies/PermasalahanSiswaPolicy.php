<?php

namespace App\Policies;

use App\Models\PermasalahanSiswa;
use App\Models\User;

class PermasalahanSiswaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('masalah.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PermasalahanSiswa $permasalahanSiswa): bool
    {
        return $user->can('masalah.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('masalah.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PermasalahanSiswa $permasalahanSiswa): bool
    {
        return $user->can('masalah.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PermasalahanSiswa $permasalahanSiswa): bool
    {
        return $user->can('masalah.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PermasalahanSiswa $permasalahanSiswa): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PermasalahanSiswa $permasalahanSiswa): bool
    {
        return false;
    }
}
