<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Services\Monitoring\MonitoringAttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonitoringAttendanceController extends Controller
{
    public function __invoke(
        Request $request,
        MonitoringAttendanceService $service,
    ) {
        $tanggal = Carbon::parse(
            $request->tanggal ?? today()
        );

        return response()->json([

            'statistics' => $service->statistics(
                tanggal: $tanggal,
                kelasId: $request->kelas,
                search: $request->search,
            ),

            'attendance' => $service->paginate(
                tanggal: $tanggal,
                kelasId: $request->kelas,
                search: $request->search,
            ),

            'classes' => Kelas::query()
                ->select('id', 'nama_kelas')
                ->orderBy('nama_kelas')
                ->pluck('nama_kelas', 'id'),

        ]);
    }
}
