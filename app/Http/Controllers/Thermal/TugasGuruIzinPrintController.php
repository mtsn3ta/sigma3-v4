<?php

namespace App\Http\Controllers\Thermal;

use App\Http\Controllers\Controller;
use App\Models\TugasGuruIzin;

class TugasGuruIzinPrintController extends Controller
{
    public function __invoke(TugasGuruIzin $tugasGuruIzin)
    {
        $tugasGuruIzin->load([

            'guru',

            'kelas',

            'petugas',

        ]);

        return view(
            'thermal.tugas-guru-izin',
            [
                'tugas' => $tugasGuruIzin,
            ]
        );
    }
}
