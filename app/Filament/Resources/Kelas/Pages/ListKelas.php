<?php

namespace App\Filament\Resources\Kelas\Pages;

use App\Filament\Resources\Kelas\KelasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Services\Kelas\GenerateClassService;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use App\Services\ActivityLogService;

class ListKelas extends ListRecords
{
    protected static string $resource = KelasResource::class;

    protected function getHeaderActions(): array
    {
        return [

            CreateAction::make()
                ->label('Tambah Kelas'),

            Action::make('generate')

                ->label('Generate Kelas')

                ->icon('heroicon-o-squares-plus')

                ->color('success')

                ->schema([

                    Radio::make('tingkat')
                        ->label('Tingkat')
                        ->options([
                            7 => 'Kelas 7',
                            8 => 'Kelas 8',
                            9 => 'Kelas 9',
                        ])
                        ->inline()
                        ->required(),

                    Radio::make('format')
                        ->label('Format')
                        ->options([
                            'huruf' => 'Huruf',
                            'angka' => 'Angka',
                        ])
                        ->inline()
                        ->default('huruf')
                        ->live()
                        ->required(),

                    Select::make('from')
                        ->label('Dari')
                        ->options(fn($get) => $get('format') === 'huruf'
                            ? collect(range('A', 'Z'))->mapWithKeys(fn($v) => [$v => $v])
                            : collect(range(1, 20))->mapWithKeys(fn($v) => [$v => $v]))
                        ->required(),

                    Select::make('to')
                        ->label('Sampai')
                        ->options(fn($get) => $get('format') === 'huruf'
                            ? collect(range('A', 'Z'))->mapWithKeys(fn($v) => [$v => $v])
                            : collect(range(1, 20))->mapWithKeys(fn($v) => [$v => $v]))
                        ->required(),

                ])

                ->action(function (array $data) {
                    $from = $data['from'];
                    $to = $data['to'];

                    if ($from > $to) {

                        Notification::make()
                            ->title('Rentang Tidak Valid')
                            ->body('Nilai "Dari" harus lebih kecil atau sama dengan "Sampai".')
                            ->danger()
                            ->send();

                        return;
                    }

                    $result = app(GenerateClassService::class)->generate(
                        tingkat: $data['tingkat'],
                        format: $data['format'],
                        from: $from,
                        to: $to,
                    );

                    ActivityLogService::log(
                        'Generate Kelas',
                        sprintf(
                            'Tingkat %s | Format %s | %s-%s | Created: %d | Skipped: %d',
                            $data['tingkat'],
                            ucfirst($data['format']),
                            $data['from'],
                            $data['to'],
                            $result['created'],
                            $result['skipped'],
                        )
                    );

                    Notification::make()
                        ->title('Generate Kelas Berhasil')
                        ->body(
                            "{$result['created']} kelas berhasil dibuat.\n" .
                                "{$result['skipped']} kelas dilewati karena sudah ada."
                        )
                        ->success()
                        ->send();
                }),

        ];
    }
    public function getTitle(): string
    {
        return 'Daftar Semua Kelas';
    }
}
