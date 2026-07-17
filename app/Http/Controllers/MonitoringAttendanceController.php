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
        // PERBAIKAN: Jika parameter tanggal kosong, gunakan tanggal hari ini berbasis Asia/Jakarta (WIB) agar melompat ke tanggal 13
        $tanggalStr = $request->tanggal ?? now('Asia/Jakarta')->toDateString();
        $tanggal = Carbon::parse($tanggalStr);

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

            // Saringan optimasi: Membatasi agar hanya memunculkan kelas yang terikat pada tahun ajaran aktif saat ini
            'classes' => Kelas::query()
                ->whereHas('kelasSiswa.tahunAjaran', fn($q) => $q->where('aktif', true))
                ->orderBy('nama_kelas')
                ->pluck('nama_kelas', 'id'),

        ]);
    }
}
