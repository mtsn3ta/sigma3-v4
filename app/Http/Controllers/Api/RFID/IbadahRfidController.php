<?php

namespace App\Http\Controllers\Api\RFID;

use App\Http\Controllers\Controller;
use App\Services\RFID\IbadahRfidService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class IbadahRfidController extends Controller
{
    public function __invoke(
        Request $request,
        IbadahRfidService $service,
    ): JsonResponse {

        $request->validate([
            'uid' => ['required', 'string'],
        ]);

        try {

            return response()->json(
                $service->scan(
                    $request->string('uid')->toString(),
                ),
            );
        } catch (Throwable $e) {

            report($e);

            return response()->json([
                'status' => false,
                'mode' => 'ibadah',
                'error' => 'system_error',
                'message' => 'Terjadi kesalahan sistem.',
            ]);
        }
    }
}
