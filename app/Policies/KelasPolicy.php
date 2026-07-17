<?php

namespace App\Policies;

use App\Models\Kelas;
use App\Models\User;

class KelasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('kelas.view');
    }

    public function view(User $user, Kelas $kelas): bool
    {
        return $user->can('kelas.view');
    }

    public function create(User $user): bool
    {
        return $user->can('kelas.manage');
    }

    public function update(User $user, Kelas $kelas): bool
    {
        return $user->can('kelas.manage');
    }

    public function delete(User $user, Kelas $kelas): bool
    {
        return $user->can('kelas.manage');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('kelas.manage');
    }
}
