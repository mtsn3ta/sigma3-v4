<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaConversation extends Model
{
    protected $fillable = [

        'phone',

        'state',

        'menu',

        'data',

    ];

    protected $casts = [

        'data' => 'array',

    ];
}
