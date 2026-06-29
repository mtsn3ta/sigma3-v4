<?php

namespace App\Policies;

use App\Models\TugasGuruIzin;
use App\Models\User;

class TugasGuruIzinPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('tugas_izin.view');
    }

    public function view(User $user, TugasGuruIzin $tugasGuruIzin): bool
    {
        return $user->can('tugas_izin.view');
    }

    public function create(User $user): bool
    {
        return $user->can('tugas_izin.create');
    }

    public function update(User $user, TugasGuruIzin $tugasGuruIzin): bool
    {
        return $user->can('tugas_izin.edit')
            && $user->pegawai?->id === $tugasGuruIzin->pegawai_id
            && $tugasGuruIzin->status === 'baru';
    }

    public function delete(User $user, TugasGuruIzin $tugasGuruIzin): bool
    {
        return $this->update($user, $tugasGuruIzin);
    }

    public function distribute(User $user): bool
    {
        return $user->can('tugas_izin.distribute');
    }

    public function restore(User $user, TugasGuruIzin $tugasGuruIzin): bool
    {
        return false;
    }

    public function forceDelete(User $user, TugasGuruIzin $tugasGuruIzin): bool
    {
        return false;
    }
}
