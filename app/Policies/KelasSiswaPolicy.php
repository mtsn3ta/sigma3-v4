<?php

namespace App\Policies;

use App\Models\KelasSiswa;
use App\Models\User;

class KelasSiswaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('kelas_siswa.view');
    }

    public function view(User $user, KelasSiswa $kelasSiswa): bool
    {
        return $user->can('kelas_siswa.view');
    }

    public function create(User $user): bool
    {
        return $user->can('kelas_siswa.manage');
    }

    public function update(User $user, KelasSiswa $kelasSiswa): bool
    {
        return $user->can('kelas_siswa.manage');
    }

    public function delete(User $user, KelasSiswa $kelasSiswa): bool
    {
        return $user->can('kelas_siswa.manage');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('kelas_siswa.manage');
    }
}
