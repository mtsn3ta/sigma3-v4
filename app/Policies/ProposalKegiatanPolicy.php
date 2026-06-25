<?php

namespace App\Policies;

use App\Models\ProposalKegiatan;
use App\Models\User;

class ProposalKegiatanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('proposal.view');
    }

    public function view(
        User $user,
        ProposalKegiatan $proposalKegiatan
    ): bool {
        return $user->can('proposal.view');
    }

    public function create(User $user): bool
    {
        return $user->can('proposal.create');
    }

    public function update(
        User $user,
        ProposalKegiatan $proposalKegiatan
    ): bool {

        if (
            in_array(
                $proposalKegiatan->status,
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

        return $user->can('proposal.edit_draft');
    }

    public function delete(
        User $user,
        ProposalKegiatan $proposalKegiatan
    ): bool {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }

    public function restore(
        User $user,
        ProposalKegiatan $proposalKegiatan
    ): bool {
        return false;
    }

    public function forceDelete(
        User $user,
        ProposalKegiatan $proposalKegiatan
    ): bool {
        return false;
    }
}
