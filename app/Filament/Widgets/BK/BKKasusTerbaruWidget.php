<?php

namespace App\Filament\Widgets\BK;

use App\Models\PermasalahanSiswa;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class BKKasusTerbaruWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    // Mengambil 1 kolom agar bisa berdampingan
    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('bk');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Kasus & Pendampingan Terbaru')
            ->description('Catatan kedisiplinan yang perlu atau sudah ditangani.')
            ->query(
                PermasalahanSiswa::query()
                    ->with('siswa')
                    ->latest('tanggal')
                    ->limit(8)
            )
            ->columns([
                // 1. Nama Siswa & Tanggal (Digabung untuk menghemat ruang horizontal)
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('danger')
                    ->weight(FontWeight::Bold)
                    ->description(fn(PermasalahanSiswa $record): string => Carbon::parse($record->tanggal)->diffForHumans())
                    ->searchable(),

                // 2. Permasalahan
                Tables\Columns\TextColumn::make('permasalahan')
                    ->label('Kasus / Permasalahan')
                    ->color('gray')
                    ->wrap()
                    ->limit(40),

                // 3. Status Penyelesaian (Mengubah IconColumn jadi Badge Premium)
                Tables\Columns\TextColumn::make('status_penyelesaian')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn($record): string => filled($record->penyelesaian) ? 'Selesai' : 'Diproses')
                    ->icon(fn(string $state): string => match ($state) {
                        'Selesai'  => 'heroicon-m-check-badge',
                        'Diproses' => 'heroicon-m-arrow-path',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Selesai'  => 'success',
                        'Diproses' => 'danger',
                    })
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped()
            ->emptyStateIcon('heroicon-o-shield-check')
            ->emptyStateHeading('Tidak Ada Catatan Kasus')
            ->emptyStateDescription('Belum ada laporan kedisiplinan siswa terbaru.');
    }
}
