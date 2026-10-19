<?php

namespace App\Http\Controllers;

use App\Services\Monitoring\LiveKbmService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DisplayKbmController extends Controller
{
    public function __construct(
        protected LiveKbmService $liveKbmService
    ) {}

    /**
     * Menampilkan halaman TV Fullscreen Live Monitoring KBM
     * atau mengembalikan JSON untuk polling AJAX (setiap 10-15 detik).
     */
    public function __invoke(Request $request): View|JsonResponse
    {
        // Parameter simulasi waktu jika ada (misal untuk testing oleh admin: ?time=08:30&day=1)
        $simulatedNow = null;
        if ($request->filled('time')) {
            try {
                $timeParts = explode(':', (string) $request->query('time'));
                $simulatedNow = Carbon::now('Asia/Jakarta')
                    ->setTime((int) ($timeParts[0] ?? 8), (int) ($timeParts[1] ?? 0), 0);
            } catch (\Throwable) {
                $simulatedNow = null;
            }
        }

        $data = $this->liveKbmService->getLiveMonitoringData($simulatedNow);

        if ($request->ajax() || $request->wantsJson() || $request->has('json')) {
            return response()->json($data);
        }

        return view('display.kbm-live', [
            'initialData' => $data,
        ]);
    }
}
