<?php

namespace App\Services\RFID;

use App\Models\RfidFailedLog;
use App\Models\Siswa;

class RfidFailedLogger
{
    public function store(
        string $mode,
        string $uid,
        string $error,
        string $message,
        ?Siswa $student = null,
    ): void {

        RfidFailedLog::create([

            'mode' => $mode,

            'uid' => $uid,

            'siswa_id' => $student?->id,

            'error' => $error,

            'message' => $message,

            'ip_address' => request()->ip(),

            // PERBAIKAN: Mengunci waktu pencatatan log eror ke Asia/Jakarta (WIB)
            'created_at' => now('Asia/Jakarta'),

        ]);
    }
}
