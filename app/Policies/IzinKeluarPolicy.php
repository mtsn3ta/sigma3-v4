<?php

namespace App\Policies;

use App\Models\IzinKeluar;
use App\Models\User;

class IzinKeluarPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('izin_keluar.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IzinKeluar $izinKeluar): bool
    {
        return $user->can('izin_keluar.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('izin_keluar.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IzinKeluar $izinKeluar): bool
    {
        return $user->can('izin_keluar.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IzinKeluar $izinKeluar): bool
    {
        return $user->can('izin_keluar.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, IzinKeluar $izinKeluar): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, IzinKeluar $izinKeluar): bool
    {
        return false;
    }
}
