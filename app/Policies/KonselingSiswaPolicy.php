<?php

namespace App\Policies;

use App\Models\KonselingSiswa;
use App\Models\User;

class KonselingSiswaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('konseling.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KonselingSiswa $konselingSiswa): bool
    {
        return $user->can('konseling.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('konseling.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KonselingSiswa $konselingSiswa): bool
    {
        return $user->can('konseling.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KonselingSiswa $konselingSiswa): bool
    {
        return $user->can('konseling.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KonselingSiswa $konselingSiswa): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KonselingSiswa $konselingSiswa): bool
    {
        return false;
    }
}
