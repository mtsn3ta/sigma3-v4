<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public static function log(
        string $aksi,
        ?string $deskripsi = null,
    ): void {

        ActivityLog::create([

            'user_id' => Auth::id(),

            'aksi' => $aksi,

            'deskripsi' => $deskripsi,

            'ip_address' => Request::ip(),

            'user_agent' => Request::userAgent(),

            'created_at' => now(),

        ]);
    }
}
