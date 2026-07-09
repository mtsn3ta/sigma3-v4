<?php

namespace App\Filament\Widgets\Piket;

use App\Models\TugasGuruIzin;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PiketTugasGuruWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('piket');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Tugas Inval Guru Berhalangan')
            ->description('Pantauan distribusi tugas kelas bagi guru yang izin hari ini.')
            ->query(
                TugasGuruIzin::query()
                    ->with(['guru', 'kelas'])
                    ->whereDate('tanggal', today())
                    ->latest()
            )
            ->columns([
                // 1. Guru & Mapel (Digabung)
                Tables\Columns\TextColumn::make('guru.nama')
                    ->label('Guru Berhalangan')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('danger')
                    ->weight(FontWeight::Bold)
                    ->description(fn(TugasGuruIzin $record): string => 'Mapel: ' . $record->mapel)
                    ->searchable(),

                // 2. Kelas & Jam (Digabung)
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas/Jam')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-academic-cap')
                    ->description(fn(TugasGuruIzin $record): string => "Jam Ke-{$record->jam_ke}"),

                // 3. Isi Tugas (Dibuat wrap agar rapi)
                Tables\Columns\TextColumn::make('isi_tugas')
                    ->label('Instruksi Tugas')
                    ->color('gray')
                    ->wrap()
                    ->limit(60),

                // 4. Status (Badge dengan Ikon)
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Distribusi')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'baru'        => 'heroicon-m-sparkles',
                        'disampaikan' => 'heroicon-m-paper-airplane',
                        'selesai'     => 'heroicon-m-check-badge',
                        default       => 'heroicon-m-minus',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'baru'        => 'warning',
                        'disampaikan' => 'info',
                        'selesai'     => 'success',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'baru'        => 'Baru',
                        'disampaikan' => 'Sudah Disampaikan',
                        'selesai'     => 'Selesai',
                        default       => '-',
                    })
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped()
            // Empty State yang menenangkan
            ->emptyStateIcon('heroicon-o-check-circle')
            ->emptyStateHeading('Semua Kelas Kondusif')
            ->emptyStateDescription('Tidak ada guru yang berhalangan hadir atau semua tugas sudah terdistribusi.');
    }
}
