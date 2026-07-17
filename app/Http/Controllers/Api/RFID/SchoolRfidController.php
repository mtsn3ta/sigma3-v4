<?php

namespace App\Http\Controllers\Api\RFID;

use App\Http\Controllers\Controller;
use App\Http\Requests\RFID\SchoolRfidRequest;
use App\Services\RFID\SchoolRfidService;
use Illuminate\Http\JsonResponse;

class SchoolRfidController extends Controller
{
    public function __invoke(
        SchoolRfidRequest $request,
        SchoolRfidService $service,
    ): JsonResponse {

        return response()->json(

            $service->scan(

                $request->validated('uid')

            )

        );
    }
}
