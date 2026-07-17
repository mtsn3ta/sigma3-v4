<?php

namespace App\Filament\Student\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class StudentPrestasiWidget extends Widget
{
    protected string $view = 'filament.student.widgets.student-prestasi';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 5;

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $siswa = $user->siswa;

        $prestasi = $siswa?->prestasi()
            ->with('ekstrakurikuler')
            ->latest('tanggal')
            ->limit(5)
            ->get() ?? collect();

        return ['prestasi' => $prestasi];
    }
}
