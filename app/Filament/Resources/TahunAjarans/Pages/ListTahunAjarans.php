<?php

namespace App\Filament\Resources\TahunAjarans\Pages;

use App\Filament\Resources\TahunAjarans\TahunAjaranResource;
use App\Services\StudentPlacement\StudentPromotionService;
use App\Support\TahunAjaranHelper;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListTahunAjarans extends ListRecords
{
    protected static string $resource = TahunAjaranResource::class;

    protected function getHeaderActions(): array
    {
        return [

            CreateAction::make()
                ->label('Tambah Tahun Ajaran'),

            Action::make('kenaikanTahunAjaran')

                ->label('Kenaikan Tahun Ajaran')

                ->icon('heroicon-o-arrow-trending-up')

                ->color('success')

                ->modalWidth('2xl')

                ->modalHeading('Proses Kenaikan Tahun Ajaran')

                ->modalDescription(
                    'Proses ini akan menaikkan seluruh siswa ke Tahun Ajaran baru. Pastikan seluruh data kelas telah benar.'
                )

                ->requiresConfirmation()

                ->schema([

                    Select::make('tahun_asal_id')

                        ->label('Tahun Ajaran Asal')

                        ->options(
                            TahunAjaranHelper::options()
                        )

                        ->searchable()

                        ->preload()

                        ->required(),

                    Select::make('tahun_tujuan_id')

                        ->label('Tahun Ajaran Tujuan')

                        ->options(
                            TahunAjaranHelper::options()
                        )

                        ->searchable()

                        ->preload()

                        ->required(),

                ])

                ->action(function (array $data) {

                    $result = app(
                        StudentPromotionService::class
                    )->execute(

                        tahunAsalId: $data['tahun_asal_id'],

                        tahunTujuanId: $data['tahun_tujuan_id'],

                    );

                    Notification::make()

                        ->title('Kenaikan Tahun Ajaran Berhasil')

                        ->body(sprintf(

                            "Naik Kelas : %d siswa\nLulus : %d siswa",

                            $result['naik'],

                            $result['lulus'],

                        ))

                        ->success()

                        ->persistent()

                        ->send();
                }),

        ];
    }

    public function getTitle(): string
    {
        return 'Daftar Tahun Ajaran';
    }
}
