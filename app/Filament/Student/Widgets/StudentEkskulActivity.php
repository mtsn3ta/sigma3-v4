<?php

namespace App\Filament\Student\Widgets;

use App\Models\KehadiranEkskul;
use App\Models\SesiKegiatan;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class StudentEkskulActivity extends Widget
{
    protected string $view = 'filament.student.widgets.student-ekskul-activity';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 7;

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $siswa = $user->siswa;

        if (! $siswa) {
            return ['jadwalTerdekat' => collect(), 'absensiTerakhir' => collect()];
        }

        // ID ekskul yang diikuti siswa (tahun ajaran aktif)
        $ekskulIds = $siswa->anggotaEkskul()
            ->whereHas('tahunAjaran', fn($q) => $q->where('aktif', true))
            ->pluck('ekstrakurikuler_id');

        // Jadwal terdekat (sesi mendatang dari ekskul yang diikuti)
        $jadwalTerdekat = SesiKegiatan::query()
            ->with('ekstrakurikuler')
            ->whereIn('ekstrakurikuler_id', $ekskulIds)
            ->where('tanggal', '>=', today())
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->limit(5)
            ->get();

        // Absensi ekskul terakhir milik siswa ini
        $absensiTerakhir = KehadiranEkskul::query()
            ->with(['sesiKegiatan.ekstrakurikuler'])
            ->where('siswa_id', $siswa->id)
            ->latest('waktu_scan')
            ->limit(5)
            ->get();

        return [
            'jadwalTerdekat' => $jadwalTerdekat,
            'absensiTerakhir' => $absensiTerakhir,
        ];
    }
}
