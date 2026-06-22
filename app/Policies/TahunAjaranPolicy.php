<?php

namespace App\Policies;

use App\Models\TahunAjaran;
use App\Models\User;

class TahunAjaranPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('tahun_ajaran.view');
    }

    public function view(User $user, TahunAjaran $tahunAjaran): bool
    {
        return $user->can('tahun_ajaran.view');
    }

    public function create(User $user): bool
    {
        return $user->can('tahun_ajaran.manage');
    }

    public function update(User $user, TahunAjaran $tahunAjaran): bool
    {
        return $user->can('tahun_ajaran.manage');
    }

    public function delete(User $user, TahunAjaran $tahunAjaran): bool
    {
        return $user->can('tahun_ajaran.manage');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('tahun_ajaran.manage');
    }
}
