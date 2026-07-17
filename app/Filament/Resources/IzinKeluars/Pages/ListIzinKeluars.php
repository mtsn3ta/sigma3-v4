<?php

namespace App\Filament\Resources\IzinKeluars\Pages;

use App\Filament\Resources\IzinKeluars\IzinKeluarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Models\IzinKeluar;
use Filament\Schemas\Components\Tabs;
use Illuminate\Database\Eloquent\Builder;

class ListIzinKeluars extends ListRecords
{
    protected static string $resource = IzinKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Izin Keluar'),
        ];
    }
    public function getTabs(): array
    {
        return [

            'keluar' => Tabs\Tab::make('Keluar')

                ->badge(
                    IzinKeluar::where(
                        'status_proses',
                        'keluar'
                    )->count()
                )

                ->modifyQueryUsing(
                    fn(Builder $query) =>

                    $query->where(
                        'status_proses',
                        'keluar'
                    )

                ),

            'kembali' => Tabs\Tab::make('Kembali')

                ->badge(
                    IzinKeluar::where(
                        'status_proses',
                        'kembali'
                    )->count()
                )

                ->modifyQueryUsing(
                    fn(Builder $query) =>

                    $query->where(
                        'status_proses',
                        'kembali'
                    )

                ),

            'pulang' => Tabs\Tab::make('Pulang')

                ->badge(
                    IzinKeluar::where(
                        'status_proses',
                        'pulang'
                    )->count()
                )

                ->modifyQueryUsing(
                    fn(Builder $query) =>

                    $query->where(
                        'status_proses',
                        'pulang'
                    )

                ),

            'semua' => Tabs\Tab::make('Semua')

                ->badge(
                    IzinKeluar::count()
                ),

        ];
    }
    public function getDefaultActiveTab(): string|int|null
    {
        return 'keluar';
    }
}
