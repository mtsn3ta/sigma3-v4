<?php

namespace App\Filament\Widgets\Pelatih;

use App\Models\AnggotaEkskul;
use App\Models\EkstrakurikulerPengampu;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class PelatihEkskulWidget extends Widget
{
    protected string $view = 'filament.widgets.pelatih.ekskul-list';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('pelatih') ?? false;
    }

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id;

        $pengampuan = EkstrakurikulerPengampu::where('pegawai_id', $pegawaiId)
            ->where('role', 'pelatih')
            ->with([
                'ekstrakurikuler',
                'ekstrakurikuler.pengampu.pegawai',
            ])
            ->get();

        $ekskulData = $pengampuan->map(function ($p) use ($pegawaiId) {
            $ekskul = $p->ekstrakurikuler;

            $timLain = $ekskul->pengampu
                ->where('pegawai_id', '!=', $pegawaiId)
                ->values();

            $jumlahAtlet = AnggotaEkskul::where('ekstrakurikuler_id', $ekskul->id)
                ->where('status', 'aktif')
                ->count();

            return [
                'ekskul'       => $ekskul,
                'tim'          => $timLain,
                'jumlahAtlet'  => $jumlahAtlet,
            ];
        });

        return ['ekskulData' => $ekskulData];
    }
}
