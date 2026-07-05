<?php

namespace App\Policies;

use App\Models\RfidFailedLog;
use App\Models\User;

class RfidFailedLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('rfid_failed_log.view');
    }

    public function view(User $user, RfidFailedLog $rfidFailedLog): bool
    {
        return $user->can('rfid_failed_log.view');
    }

    public function create(User $user): bool
    {
        return $user->can('rfid_failed_log.create');
    }

    public function update(User $user, RfidFailedLog $rfidFailedLog): bool
    {
        return $user->can('rfid_failed_log.edit');
    }

    public function delete(User $user, RfidFailedLog $rfidFailedLog): bool
    {
        return $user->can('rfid_failed_log.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('rfid_failed_log.delete');
    }

    public function restore(User $user, RfidFailedLog $rfidFailedLog): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, RfidFailedLog $rfidFailedLog): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, RfidFailedLog $rfidFailedLog): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return false;
    }
}
