<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GuruBerhalangan extends Page
{
    protected string $view = 'filament.pages.guru-berhalangan';

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedClipboardDocument;

    protected static ?string $navigationLabel =
    'Tugas Guru Izin';

    protected static string|\UnitEnum|null $navigationGroup =
    'Guru';

    protected static ?int $navigationSort = 2;
    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->hasRole('guru') ?? false;
    }
    public function getTitle(): string
    {
        return 'Riwayat Guru Berhalangan';
    }
    protected function getHeaderActions(): array
    {
        return [

            Action::make('tambahGuruBerhalangan')

                ->label('Buat Tugas Guru')

                ->icon('heroicon-m-plus')

                ->color('primary')

                ->action(
                    fn() => $this->dispatch(
                        'tambah-guru-berhalangan'
                    )
                ),

        ];
    }
}
