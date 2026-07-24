<?php

namespace App\Filament\Widgets\KepMad;

use App\Filament\Resources\LaporanBulanans\LaporanBulananResource;
use App\Filament\Resources\LaporanKegiatans\LaporanKegiatanResource;
use App\Filament\Resources\Prestasis\PrestasiResource;
use App\Filament\Resources\ProposalKegiatans\ProposalKegiatanResource;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class KepMadQuickMenuWidget extends Widget
{
    protected string $view = 'filament.widgets.kep-mad.quick-menu';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user?->hasRole('kepala_madrasah') ?? false;
    }

    protected function getViewData(): array
    {
        return [
            'title' => 'Akses Cepat (Approval)',
            'headerIcon' => 'heroicon-o-shield-check',
            'theme' => 'emerald',
            'items' => [
                ['label' => 'Approval Proposal', 'icon' => 'heroicon-o-document-check', 'url' => ProposalKegiatanResource::getUrl()],
                ['label' => 'Approval Laporan', 'icon' => 'heroicon-o-clipboard-document-check', 'url' => LaporanKegiatanResource::getUrl()],
                ['label' => 'Laporan Bulanan', 'icon' => 'heroicon-o-calendar-days', 'url' => LaporanBulananResource::getUrl()],
                ['label' => 'Prestasi', 'icon' => 'heroicon-o-trophy', 'url' => PrestasiResource::getUrl()],
            ],
        ];
    }
}
