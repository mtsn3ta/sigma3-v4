<?php

namespace App\Policies;

use App\Models\LaporanBulanan;
use App\Models\User;

class LaporanBulananPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('laporan_bulanan.view');
    }

    public function view(
        User $user,
        LaporanBulanan $laporanBulanan
    ): bool {
        return $user->can('laporan_bulanan.view');
    }

    public function create(User $user): bool
    {
        return $user->can('laporan_bulanan.create');
    }

    public function update(
        User $user,
        LaporanBulanan $laporanBulanan
    ): bool {

        if (
            in_array(
                $laporanBulanan->status,
                [
                    'pending_pembina',
                    'pending_waka',
                    'pending_kepala',
                    'approved',
                ]
            )
        ) {
            return false;
        }

        return $user->can('laporan_bulanan.edit_draft');
    }

    /**
     * Submit
     */
    public function submit(
        User $user,
        LaporanBulanan $laporanBulanan
    ): bool {
        return $user->can('laporan_bulanan.submit');
    }

    /**
     * Review
     */
    public function review(
        User $user,
        LaporanBulanan $laporanBulanan
    ): bool {
        return $user->can('laporan_bulanan.review');
    }

    /**
     * Approve
     */
    public function approve(
        User $user,
        LaporanBulanan $laporanBulanan
    ): bool {
        return $user->can('laporan_bulanan.approve');
    }

    /**
     * Reject
     */
    public function reject(
        User $user,
        LaporanBulanan $laporanBulanan
    ): bool {
        return $user->can('laporan_bulanan.reject');
    }

    /**
     * Request Revision
     */
    public function revision(
        User $user,
        LaporanBulanan $laporanBulanan
    ): bool {
        return $user->can('laporan_bulanan.revision');
    }

    public function delete(
        User $user,
        LaporanBulanan $laporanBulanan
    ): bool {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }

    public function restore(
        User $user,
        LaporanBulanan $laporanBulanan
    ): bool {
        return false;
    }

    public function forceDelete(
        User $user,
        LaporanBulanan $laporanBulanan
    ): bool {
        return false;
    }
}
