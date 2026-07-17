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
        return $user->can('proposal.edit_draft');
    }

    /**
     * Submit Proposal
     */
    public function submit(
        User $user,
        ProposalKegiatan $proposalKegiatan
    ): bool {
        return $user->can('proposal.submit');
    }

    /**
     * Review Proposal
     */
    public function review(
        User $user,
        ProposalKegiatan $proposalKegiatan
    ): bool {
        return $user->can('proposal.review');
    }

    /**
     * Approve Proposal
     */
    public function approve(
        User $user,
        ProposalKegiatan $proposalKegiatan
    ): bool {
        return $user->can('proposal.approve');
    }

    /**
     * Reject Proposal
     */
    public function reject(
        User $user,
        ProposalKegiatan $proposalKegiatan
    ): bool {
        return $user->can('proposal.reject');
    }

    /**
     * Request Revision
     */
    public function revision(
        User $user,
        ProposalKegiatan $proposalKegiatan
    ): bool {
        return $user->can('proposal.revision');
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
