<?php

namespace App\Filament\Pages;

use App\Models\UmpanBalikGuru as UmpanBalikGuruModel;
use App\Support\ActiveRole;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class UmpanBalik extends Page
{
    protected string $view = 'filament.pages.umpan-balik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftEllipsis;

    protected static ?string $navigationLabel = 'Umpan Balik';

    protected static ?string $title = 'Umpan Balik Kepala Madrasah';

    protected static ?string $slug = 'umpan-balik';

    protected static string|\UnitEnum|null $navigationGroup = 'Guru';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return ActiveRole::is('guru');
    }

    public static function getNavigationBadge(): ?string
    {
        $pegawaiId = Auth::user()?->pegawai?->id;

        if (! $pegawaiId) {
            return null;
        }

        $jumlah = UmpanBalikGuruModel::query()
            ->where('pegawai_id', $pegawaiId)
            ->belumDibaca()
            ->count();

        return $jumlah > 0 ? (string) $jumlah : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getNavigationBadgeTooltip(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return 'Umpan balik yang belum dibaca';
    }
}
