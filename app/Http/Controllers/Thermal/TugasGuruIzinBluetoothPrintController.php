<?php

namespace App\Http\Controllers\Thermal;

use App\Http\Controllers\Controller;
use App\Models\TugasGuruIzin;
use App\Services\Printer\StrukPrinter;

class TugasGuruIzinBluetoothPrintController extends Controller
{
    public function __invoke(TugasGuruIzin $tugasGuruIzin)
    {
        $tugasGuruIzin->load([
            'guru',
            'kelas',
            'petugas',
        ]);

        $intentUrl = StrukPrinter::tugasGuruIzin($tugasGuruIzin);

        return redirect()->away($intentUrl);
    }
}
