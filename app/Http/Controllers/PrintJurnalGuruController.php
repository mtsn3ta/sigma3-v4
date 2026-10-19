<?php

namespace App\Http\Controllers;

use App\Models\JurnalMengajar;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrintJurnalGuruController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var \App\Models\Pegawai|null $pegawai */
        $pegawai = auth()->user()?->pegawai;

        abort_if(! $pegawai, 403, 'Akun ini tidak terhubung dengan data guru.');

        $data = $request->validate([
            'tanggal_mulai'   => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'kelas_id'        => ['nullable', 'integer'],
        ]);

        $mulai = Carbon::parse($data['tanggal_mulai'] ?? now('Asia/Jakarta')->startOfMonth()->toDateString())->startOfDay();
        $selesai = Carbon::parse($data['tanggal_selesai'] ?? now('Asia/Jakarta')->toDateString())->endOfDay();

        $jurnal = JurnalMengajar::query()
            ->with(['kelas', 'kehadiranPembelajaran.siswa'])
            ->where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', '>=', $mulai->toDateString())
            ->whereDate('tanggal', '<=', $selesai->toDateString())
            ->when(! empty($data['kelas_id']), fn ($query) => $query->where('kelas_id', $data['kelas_id']))
            ->orderBy('tanggal')
            ->orderBy('jam_ke')
            ->get();

        $pdf = Pdf::loadView('pdf.jurnal-guru', [
            'pegawai' => $pegawai,
            'mulai'   => $mulai,
            'selesai' => $selesai,
            'jurnal'  => $jurnal,
        ]);

        return $pdf
            ->setPaper('a4', 'portrait')
            ->download('jurnal-mengajar-' . $mulai->format('Ymd') . '-' . $selesai->format('Ymd') . '.pdf');
    }
}
