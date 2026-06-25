<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalApprovalLog extends Model
{
    protected $table = 'proposal_approval_log';

    protected $fillable = [
        'proposal_kegiatan_id',
        'user_id',
        'action',
        'notes',
    ];

    public function proposal()
    {
        return $this->belongsTo(
            ProposalKegiatan::class,
            'proposal_kegiatan_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
