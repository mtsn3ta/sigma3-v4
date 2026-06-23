<?php

namespace App\Policies;

use App\Models\KehadiranEkskul;
use App\Models\User;

class KehadiranEkskulPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('kehadiran_ekskul.view');
    }

    public function view(User $user, KehadiranEkskul $kehadiranEkskul): bool
    {
        return $user->can('kehadiran_ekskul.view');
    }

    public function create(User $user): bool
    {
        return $user->can('kehadiran_ekskul.manage');
    }

    public function update(User $user, KehadiranEkskul $kehadiranEkskul): bool
    {
        return $user->can('kehadiran_ekskul.manage');
    }

    public function delete(User $user, KehadiranEkskul $kehadiranEkskul): bool
    {
        return $user->can('kehadiran_ekskul.manage');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('kehadiran_ekskul.manage');
    }

    public function restore(User $user, KehadiranEkskul $kehadiranEkskul): bool
    {
        return false;
    }

    public function forceDelete(User $user, KehadiranEkskul $kehadiranEkskul): bool
    {
        return false;
    }
}
