<?php

namespace App\Filament\Widgets\BK;

use App\Models\IzinKeluar;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class BKIzinKeluarWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    // Mengambil 1 kolom agar bisa berdampingan
    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('bk');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Izin Keluar Hari Ini')
            ->description('Mobilitas siswa di luar madrasah pada hari ini.')
            ->query(
                IzinKeluar::query()
                    ->with(['siswa', 'pegawai'])
                    ->whereDate('tanggal', today())
                    ->latest()
            )
            ->columns([
                // 1. Siswa & Tujuan (Digabung agar tabel tidak memanjang)
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->label('Siswa & Tujuan')
                    ->icon('heroicon-m-user')
                    ->iconColor('primary')
                    ->weight(FontWeight::Bold)
                    ->description(fn(IzinKeluar $record): string => str($record->tujuan)->limit(30))
                    ->searchable(),

                // 2. Waktu Keluar - Kembali (Digabung, font Monospace)
                Tables\Columns\TextColumn::make('waktu_izin')
                    ->label('Waktu')
                    ->fontFamily(FontFamily::Mono)
                    ->icon('heroicon-m-clock')
                    ->color('gray')
                    ->getStateUsing(function (IzinKeluar $record): string {
                        $keluar = $record->jam_keluar ? Carbon::parse($record->jam_keluar)->format('H:i') : '--:--';
                        $kembali = $record->jam_kembali ? Carbon::parse($record->jam_kembali)->format('H:i') : '...';
                        return "{$keluar} - {$kembali}";
                    }),

                // 3. Status (Dilengkapi Ikon Dinamis)
                Tables\Columns\TextColumn::make('status_proses')
                    ->label('Status')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'keluar'  => 'heroicon-m-arrow-right-start-on-rectangle',
                        'kembali' => 'heroicon-m-check-badge',
                        'pulang'  => 'heroicon-m-home',
                        default   => 'heroicon-m-minus',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'keluar'  => 'warning',
                        'kembali' => 'success',
                        'pulang'  => 'info',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'keluar'  => 'Di Luar',
                        'kembali' => 'Kembali',
                        'pulang'  => 'Pulang',
                        default   => '-',
                    })
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped()
            ->emptyStateIcon('heroicon-o-check-circle')
            ->emptyStateHeading('Kehadiran Penuh')
            ->emptyStateDescription('Belum ada siswa yang meminta izin keluar hari ini.');
    }
}
