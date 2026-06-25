<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisionLog extends Model
{
    protected $fillable = [

        'user_id',

        'status_from',

        'status_to',

        'notes',
    ];

    public function revisionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
