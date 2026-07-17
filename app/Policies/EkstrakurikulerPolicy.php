<?php

namespace App\Policies;

use App\Models\Ekstrakurikuler;
use App\Models\User;

class EkstrakurikulerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('ekskul.view');
    }

    public function view(User $user, Ekstrakurikuler $ekstrakurikuler): bool
    {
        return $user->can('ekskul.view');
    }

    public function create(User $user): bool
    {
        return $user->can('ekskul.manage');
    }

    public function update(User $user, Ekstrakurikuler $ekstrakurikuler): bool
    {
        return $user->can('ekskul.manage');
    }

    public function delete(User $user, Ekstrakurikuler $ekstrakurikuler): bool
    {
        return $user->can('ekskul.manage');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('ekskul.manage');
    }
}
