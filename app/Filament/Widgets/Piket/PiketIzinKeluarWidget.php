<?php

namespace App\Filament\Widgets\Piket;

use App\Models\IzinKeluar;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class PiketIzinKeluarWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    // Diset 1 agar bisa berdampingan dengan widget kehadiran
    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('piket');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Log Izin Keluar Siswa')
            ->description('Pantau siswa yang keluar area sekolah hari ini.')
            ->query(
                IzinKeluar::query()
                    ->with(['siswa', 'pegawai'])
                    ->whereDate('tanggal', today())
                    ->latest()
            )
            ->columns([
                // 1. Siswa & Tujuan (Digabung)
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->label('Siswa & Tujuan')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('primary')
                    ->weight(FontWeight::Bold)
                    ->description(fn(IzinKeluar $record): string => str($record->tujuan)->limit(30))
                    ->searchable(),

                // 2. Waktu (Digabung format Monospace)
                Tables\Columns\TextColumn::make('waktu_izin')
                    ->label('Waktu (Keluar - Kembali)')
                    ->fontFamily(FontFamily::Mono)
                    ->icon('heroicon-m-clock')
                    ->getStateUsing(function (IzinKeluar $record): string {
                        $keluar = $record->jam_keluar ? Carbon::parse($record->jam_keluar)->format('H:i') : '--:--';
                        $kembali = $record->jam_kembali ? Carbon::parse($record->jam_kembali)->format('H:i') : '...';
                        return "{$keluar} - {$kembali}";
                    }),

                // 3. Status (Badge Premium)
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
            // Empty State
            ->emptyStateIcon('heroicon-o-shield-check')
            ->emptyStateHeading('Semua Siswa Terpantau')
            ->emptyStateDescription('Belum ada data siswa yang mengajukan izin keluar hari ini.');
    }
}
