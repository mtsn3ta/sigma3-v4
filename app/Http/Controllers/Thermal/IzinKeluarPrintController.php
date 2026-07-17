<?php

namespace App\Http\Controllers\Thermal;

use App\Http\Controllers\Controller;
use App\Models\IzinKeluar;

class IzinKeluarPrintController extends Controller
{
    public function __invoke(IzinKeluar $izinKeluar)
    {
        $izinKeluar->load([

            'siswa',

            'pegawai',

        ]);

        return view(
            'thermal.izin-keluar',
            compact('izinKeluar')
        );
    }
}
