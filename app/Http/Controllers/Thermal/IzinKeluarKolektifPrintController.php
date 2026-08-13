<?php

namespace App\Http\Controllers\Thermal; // <--- PERBAIKAN NAMESPACE

use App\Http\Controllers\Controller;
use App\Models\IzinKeluar;
use Illuminate\Http\Request;

class IzinKeluarKolektifPrintController extends Controller
{
    public function __invoke(Request $request)
    {
        $idsString = $request->query('ids');

        if (!$idsString) {
            abort(404, 'Tidak ada data siswa yang dipilih.');
        }

        $idsArray = explode(',', $idsString);

        $records = IzinKeluar::with(['siswa.kelasAktif.kelas', 'pegawai'])
            ->whereIn('id', $idsArray)
            ->get();

        if ($records->isEmpty()) {
            abort(404, 'Data tidak ditemukan.');
        }

        IzinKeluar::whereIn('id', $idsArray)
            ->whereNull('waktu_cetak')
            ->update(['waktu_cetak' => now('Asia/Jakarta')]);

        $masterRecord = $records->first();

        return view('thermal.izin-keluar-kolektif', compact('records', 'masterRecord'));
    }
}
