<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\View\View;

class ParentPortalController extends Controller
{
    public function __invoke(string $nisn): View
    {
        $student = Siswa::with([
            'kelasAktif.kelas',
            'parentLeaveRequests' => fn($q) => $q->latest(),
            'permasalahan' => fn($q) => $q
                ->with('pegawai')
                ->latest('tanggal'),
            'konseling' => fn($q) => $q
                ->with('pegawai')
                ->latest('tanggal'),
            'kehadiranSekolah' => fn($q) => $q
                ->latest('tanggal')
                ->take(10),
        ])
            ->where('nisn', $nisn)
            ->firstOrFail();

        return view('parent-portal.index', [
            'student' => $student,
        ]);
    }
}
