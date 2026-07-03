<?php

namespace App\Http\Controllers\API\RFID;

use App\Http\Controllers\Controller;
use App\Services\RFID\EkstrakurikulerRfidService;
use Illuminate\Http\Request;

class EkstrakurikulerRfidController extends Controller
{
    public function __construct(
        protected EkstrakurikulerRfidService $service,
    ) {}

    public function scan(Request $request)
    {
        $request->validate([
            'uid' => ['required', 'string'],
        ]);

        return response()->json(
            $this->service->scan(
                $request->string('uid')
            )
        );
    }
}
