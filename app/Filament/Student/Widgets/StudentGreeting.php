<?php

namespace App\Filament\Student\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class StudentGreeting extends Widget
{
    protected string $view = 'filament.student.widgets.student-greeting';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -10;

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $siswa = $user->siswa;

        $hour = Carbon::now()->hour;

        $greeting = match (true) {
            $hour < 11 => 'Selamat Pagi',
            $hour < 15 => 'Selamat Siang',
            $hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };

        return [
            'greeting' => $greeting,
            'nama' => $siswa?->nama ?? $user->username,
            'nisn' => $siswa?->nisn ?? '-',
            'foto' => $siswa?->foto
                ? asset('storage/' . $siswa->foto)
                : null,
            'initial' => strtoupper(substr($siswa?->nama ?? 'S', 0, 1)),
            'namaKelas' => $siswa?->nama_kelas_aktif ?? '-',
            'tahunAjaranAktif' => $siswa?->kelasAktif?->tahunAjaran?->tahun ?? '-',
        ];
    }
}
