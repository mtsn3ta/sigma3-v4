<?php

namespace App\Policies;

use App\Models\EkstrakurikulerPengampu;
use App\Models\User;

class EkstrakurikulerPengampuPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('pengampu.view');
    }

    public function view(User $user, EkstrakurikulerPengampu $ekstrakurikulerPengampu): bool
    {
        return $user->can('pengampu.view');
    }

    public function create(User $user): bool
    {
        return $user->can('pengampu.manage');
    }

    public function update(User $user, EkstrakurikulerPengampu $ekstrakurikulerPengampu): bool
    {
        return $user->can('pengampu.manage');
    }

    public function delete(User $user, EkstrakurikulerPengampu $ekstrakurikulerPengampu): bool
    {
        return $user->can('pengampu.manage');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('pengampu.manage');
    }

    public function restore(User $user, EkstrakurikulerPengampu $ekstrakurikulerPengampu): bool
    {
        return $user->can('pengampu.manage');
    }

    public function forceDelete(User $user, EkstrakurikulerPengampu $ekstrakurikulerPengampu): bool
    {
        return $user->can('pengampu.manage');
    }
}
