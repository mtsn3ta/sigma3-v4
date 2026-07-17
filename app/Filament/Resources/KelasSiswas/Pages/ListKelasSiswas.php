<?php

namespace App\Filament\Resources\KelasSiswas\Pages;

use App\Filament\Resources\KelasSiswas\KelasSiswaResource;
use App\Filament\Resources\KelasSiswas\Widgets\KelasSiswaOverview;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use App\Support\SiswaHelper;
use App\Support\KelasHelper;
use App\Services\StudentPlacement\StudentPlacementService;
use Filament\Notifications\Notification;
use App\Services\ActivityLogService;
use App\Models\Kelas;

class ListKelasSiswas extends ListRecords
{
    protected static string $resource = KelasSiswaResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            KelasSiswaOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [

            Action::make('penempatanSiswa')

                ->label('Kelas Siswa')

                ->icon('heroicon-o-user-plus')

                ->color('success')

                ->modalWidth('3xl')

                ->schema([

                    Select::make('kelas_id')

                        ->label('Kelas')

                        ->options(
                            KelasHelper::options()
                        )

                        ->searchable()

                        ->preload()

                        ->live()

                        ->required(),

                    Select::make('siswa_ids')

                        ->label('Siswa')

                        ->multiple()

                        ->searchable()

                        ->preload()

                        ->options(fn() => SiswaHelper::belumMemilikiKelas())

                        ->required(),

                ])

                ->action(function (array $data) {

                    if (empty($data['siswa_ids'])) {

                        Notification::make()
                            ->title('Tidak ada siswa dipilih')
                            ->danger()
                            ->send();

                        return;
                    }

                    $kelas = Kelas::findOrFail($data['kelas_id']);

                    $result = app(StudentPlacementService::class)
                        ->assign(
                            kelasId: $data['kelas_id'],
                            siswaIds: $data['siswa_ids'],
                        );

                    ActivityLogService::log(
                        'Penempatan Siswa',
                        sprintf(
                            'Kelas %s | Berhasil: %d | Dilewati: %d',
                            $kelas->nama_kelas,
                            $result['created'],
                            $result['skipped'],
                        )
                    );

                    Notification::make()
                        ->title('Penempatan Siswa Berhasil')
                        ->body(
                            "Kelas: {$kelas->nama_kelas}\n\n" .
                                "{$result['created']} siswa berhasil ditempatkan.\n" .
                                "{$result['skipped']} siswa dilewati karena sudah memiliki penempatan pada Tahun Ajaran aktif."
                        )
                        ->success()
                        ->send();
                }),

        ];
    }

    public function getTitle(): string
    {
        return 'Daftar Kelas Siswa';
    }
}
