<?php

namespace App\Http\Controllers\Thermal;

use App\Http\Controllers\Controller;
use App\Models\IzinKeluar;
use App\Services\Printer\StrukPrinter;

class IzinKeluarBluetoothPrintController extends Controller
{
    public function __invoke(IzinKeluar $izinKeluar)
    {
        $izinKeluar->load([
            'siswa.kelasAktif.kelas',
            'pegawai',
        ]);

        // Tandai sudah dicetak — dipakai IzinKeluarsTable untuk sembunyikan
        // EditAction setelah struk keluar. Hapus blok ini kalau waktu_cetak
        // memang sudah diisi dari tempat lain.
        if (blank($izinKeluar->waktu_cetak)) {
            $izinKeluar->update(['waktu_cetak' => now('Asia/Jakarta')]);
        }

        $intentUrl = StrukPrinter::izinKeluar($izinKeluar);

        return redirect()->away($intentUrl);
    }
}
