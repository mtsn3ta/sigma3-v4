<?php

namespace App\Filament\Widgets\Pembina;

use App\Models\AnggotaEkskul;
use App\Models\EkstrakurikulerPengampu;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class PembinaEkskulWidget extends Widget
{
    protected string $view = 'filament.widgets.pembina.ekskul-list';
    protected int|string|array $columnSpan = 1;
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('pembina');
    }

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id;

        // Semua ekskul yang diampu sebagai pembina, eager load ekskul & semua pengampu
        $pengampuan = EkstrakurikulerPengampu::where('pegawai_id', $pegawaiId)
            ->where('role', 'pembina')
            ->with([
                'ekstrakurikuler',
                'ekstrakurikuler.pengampu.pegawai', // semua pengampu ekskul ini
            ])
            ->get();

        $ekskulData = $pengampuan->map(function ($p) use ($pegawaiId) {
            $ekskul = $p->ekstrakurikuler;

            // Semua pengampu ekskul ini selain diri sendiri
            $timLain = $ekskul->pengampu
                ->where('pegawai_id', '!=', $pegawaiId)
                ->values();

            // Jumlah anggota aktif
            $jumlahAnggota = AnggotaEkskul::where('ekstrakurikuler_id', $ekskul->id)
                ->where('status', 'aktif')
                ->count();

            return [
                'ekskul'       => $ekskul,
                'tim'          => $timLain,
                'jumlahAnggota' => $jumlahAnggota,
            ];
        });

        return ['ekskulData' => $ekskulData];
    }
}
