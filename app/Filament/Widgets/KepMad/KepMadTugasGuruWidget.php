<?php

namespace App\Filament\Widgets\KepMad;

use App\Models\TugasGuruIzin;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class KepMadTugasGuruWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('kepala_madrasah') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Tugas Guru Izin / Inval')
            ->description('Pemantauan instruksi dan pemberian tugas dari guru yang berhalangan hadir.')
            ->query(
                TugasGuruIzin::query()
                    ->with(['guru', 'kelas'])
                    ->latest('tanggal')
                    ->limit(10)
            )
            ->columns([
                // 1. Guru & Mata Pelajaran (Digabung agar lebih rapi)
                Tables\Columns\TextColumn::make('guru.nama')
                    ->label('Guru Berhalangan')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('primary')
                    ->weight(FontWeight::Bold)
                    ->description(fn(TugasGuruIzin $record): string => 'Mapel: ' . $record->mapel)
                    ->searchable(),

                // 2. Kelas (Dengan ikon)
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-academic-cap'),

                // 3. Jam Ke (Dibuat gaya badge agar konsisten dengan SaaS UI)
                Tables\Columns\TextColumn::make('jam_ke')
                    ->label('Jam Ke')
                    ->badge()
                    ->color('gray')
                    ->icon('heroicon-m-clock')
                    ->formatStateUsing(fn(string $state): string => "Ke-{$state}")
                    ->alignCenter(),

                // 4. Status (Dilengkapi dengan ikon dinamis)
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Eksekusi')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'baru'         => 'heroicon-m-sparkles',
                        'disampaikan'  => 'heroicon-m-paper-airplane',
                        'selesai'      => 'heroicon-m-check-badge',
                        default        => 'heroicon-m-minus',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'baru'         => 'warning',
                        'disampaikan'  => 'info',
                        'selesai'      => 'success',
                        default        => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'baru'         => 'Baru',
                        'disampaikan'  => 'Sudah Disampaikan',
                        'selesai'      => 'Selesai',
                        default        => '-',
                    }),

                // 5. Tanggal (Dipindah ke ujung dengan format time-ago)
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->icon('heroicon-m-calendar-days')
                    ->description(fn(TugasGuruIzin $record): string => \Carbon\Carbon::parse($record->tanggal)->diffForHumans())
                    ->sortable()
                    ->alignEnd(),
            ])
            ->paginated(false)
            // Menambahkan aksen garis zebra agar mudah dibaca oleh eksekutif
            ->striped()
            // Tampilan khusus saat tidak ada guru yang izin
            ->emptyStateIcon('heroicon-o-check-circle')
            ->emptyStateHeading('Semua Guru Hadir')
            ->emptyStateDescription('Saat ini tidak ada laporan tugas izin atau guru yang berhalangan hadir.');
    }
}
