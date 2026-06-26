<?php

namespace App\Http\Controllers;

use App\Models\LaporanKegiatan;
use App\Services\LaporanKegiatanPdfService;

class LaporanKegiatanPdfController
{
    public function __invoke(
        LaporanKegiatan $laporan
    ) {
        return LaporanKegiatanPdfService::download($laporan);
    }
}
