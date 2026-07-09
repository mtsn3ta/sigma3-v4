<?php

namespace App\Filament\Widgets\Waka;

use App\Models\PermasalahanSiswa;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class WakaKasusTerbaruWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('waka_kesiswaan');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Catatan Kedisiplinan & Kasus Terbaru')
            ->description('Pantauan kasus pelanggaran atau permasalahan siswa yang membutuhkan tindak lanjut.')
            ->query(
                PermasalahanSiswa::query()
                    ->with('siswa')
                    ->latest('tanggal')
                    ->limit(8)
            )
            ->columns([
                // 1. Nama Siswa
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('danger') // Warna merah untuk mengindikasikan perhatian khusus
                    ->weight(FontWeight::Bold)
                    ->searchable(),

                // 2. Permasalahan (Dibuat wrap agar deskripsi masalah mudah dibaca)
                Tables\Columns\TextColumn::make('permasalahan')
                    ->label('Kasus / Pelanggaran')
                    ->color('gray')
                    ->wrap()
                    ->limit(60),

                // 3. Status Penyelesaian (Diubah dari Icon boolean menjadi Badge Premium)
                Tables\Columns\TextColumn::make('status_penyelesaian')
                    ->label('Tindak Lanjut')
                    ->badge()
                    ->getStateUsing(fn($record): string => filled($record->penyelesaian) ? 'Selesai' : 'Menunggu Proses')
                    ->icon(fn(string $state): string => match ($state) {
                        'Selesai'         => 'heroicon-m-check-badge',
                        'Menunggu Proses' => 'heroicon-m-arrow-path',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Selesai'         => 'success',
                        'Menunggu Proses' => 'danger',
                    }),

                // 4. Tanggal (Dipindah ke ujung, dilengkapi format time-ago)
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal Kejadian')
                    ->date('d M Y')
                    ->icon('heroicon-m-calendar-days')
                    ->description(fn(PermasalahanSiswa $record): string => \Carbon\Carbon::parse($record->tanggal)->diffForHumans())
                    ->sortable()
                    ->alignEnd(),
            ])
            ->paginated(false)
            // Aksen zebra untuk kerapian membaca baris
            ->striped()
            // Empty State yang positif (berita baik jika tidak ada kasus)
            ->emptyStateIcon('heroicon-o-shield-check')
            ->emptyStateHeading('Tidak Ada Catatan Kasus')
            ->emptyStateDescription('Kondisi madrasah tertib. Belum ada laporan permasalahan kedisiplinan siswa terbaru.');
    }
}
