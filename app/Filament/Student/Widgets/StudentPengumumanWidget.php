<?php

namespace App\Filament\Student\Widgets;

use App\Models\Pengumuman;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class StudentPengumumanWidget extends Widget
{
    protected string $view = 'filament.student.widgets.student-pengumuman';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 6;

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $siswa = $user->siswa;

        // ID ekskul yang diikuti siswa
        $ekskulIds = $siswa
            ?->anggotaEkskul()
            ->pluck('ekstrakurikuler_id')
            ->toArray() ?? [];

        $pengumuman = Pengumuman::query()
            ->where('status', 'publish')
            ->where(function ($query) use ($ekskulIds) {
                // Pengumuman umum (tanpa ekskul)
                $query->whereNull('ekstrakurikuler_id');

                // + pengumuman ekskul yang diikuti siswa
                if (! empty($ekskulIds)) {
                    $query->orWhereIn('ekstrakurikuler_id', $ekskulIds);
                }
            })
            ->where('tanggal_mulai', '<=', today())
            ->where('tanggal_selesai', '>=', today())
            ->orderByDesc('is_pinned')
            ->orderByDesc('tanggal_mulai')
            ->limit(5)
            ->get();

        return ['pengumuman' => $pengumuman];
    }
}
