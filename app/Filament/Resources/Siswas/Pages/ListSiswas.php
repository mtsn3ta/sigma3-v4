<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Exports\StudentTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Siswa'),
            Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->visible(function () {

                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();

                    return $user?->hasRole('super_admin') ?? false;
                })
                ->action(function () {

                    return Excel::download(
                        new StudentTemplateExport(),
                        'Template Import Siswa.xlsx',
                    );
                }),

            Action::make('import')
                ->label('Import Siswa')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->visible(function () {

                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();

                    return $user?->hasRole('super_admin') ?? false;
                })
                ->url(
                    SiswaResource::getUrl('import')
                ),
        ];
    }
    public function getTitle(): string
    {
        return 'Daftar Semua Siswa';
    }
}
