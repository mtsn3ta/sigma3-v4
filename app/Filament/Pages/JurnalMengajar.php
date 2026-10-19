<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class JurnalMengajar extends Page
{
    protected string $view = 'filament.pages.jurnal-mengajar';

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Jurnal Mengajar';

    protected static string|\UnitEnum|null $navigationGroup = 'Guru';

    protected static ?int $navigationSort = 1;
    public function getTitle(): string
    {
        return 'Jurnal Mengajar & Presensi Kelas';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola rekam jejak KBM, isi jurnal pembelajaran harian, dan catat presensi siswa di kelas.';
    }
    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasRole('guru') ?? false;
    }
}
