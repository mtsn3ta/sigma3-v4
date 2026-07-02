<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Services\Monitoring\MonitoringAttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MonitoringAttendanceController extends Controller
{
    public function index(
        Request $request,
        MonitoringAttendanceService $service,
    ) {
        $tanggal = Carbon::parse(
            $request->tanggal ?? today()
        );

        return Inertia::render('Monitoring', [

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

            'filters' => [
                'tanggal' => $tanggal->toDateString(),
                'kelas' => $request->kelas,
                'search' => $request->search,
            ],

            'classes' => Kelas::query()
                ->select('id', 'nama_kelas')
                ->orderBy('nama_kelas')
                ->pluck('nama_kelas', 'id'),

        ]);
    }
}
