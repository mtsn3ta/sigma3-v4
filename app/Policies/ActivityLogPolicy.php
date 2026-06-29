<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('activity_logs.view');
    }

    public function view(User $user, ActivityLog $activityLog): bool
    {
        return $user->can('activity_logs.view');
    }

    public function create(User $user): bool
    {
        return $user->can('activity_logs.manage');
    }

    public function update(User $user, ActivityLog $activityLog): bool
    {
        return $user->can('activity_logs.manage');
    }

    public function delete(User $user, ActivityLog $activityLog): bool
    {
        return $user->can('activity_logs.manage');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('activity_logs.manage');
    }
}
