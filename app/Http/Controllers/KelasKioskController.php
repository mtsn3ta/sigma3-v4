<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Support\JamPelajaranHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KelasKioskController extends Controller
{
    /**
     * Halaman Web Kiosk Kelas untuk Tap Kartu RFID Guru.
     * Dapat dijalankan pada PC / Laptop / Tablet guru di ruang kelas.
     */
    public function __invoke(Request $request): View
    {
        $now = Carbon::now('Asia/Jakarta');
        $slot = JamPelajaranHelper::getCurrentSlot($now);

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        // Cari kelas terpilih jika ada di query param ?kelas=7A atau ?kelas_id=12
        $selectedKelas = null;
        if ($request->filled('kelas_id')) {
            $selectedKelas = Kelas::find($request->query('kelas_id'));
        } elseif ($request->filled('kelas')) {
            $selectedKelas = Kelas::where('nama_kelas', $request->query('kelas'))->first();
        }

        return view('rfid.kelas-kiosk', [
            'kelasList' => $kelasList,
            'selectedKelas' => $selectedKelas,
            'slot' => $slot,
        ]);
    }
}
