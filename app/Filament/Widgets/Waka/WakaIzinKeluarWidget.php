<?php

namespace App\Filament\Widgets\Waka;

use App\Models\IzinKeluar;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class WakaIzinKeluarWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('waka_kesiswaan') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pantauan Izin Keluar (Hari Ini)')
            ->description('Pemantauan real-time mobilitas siswa yang keluar dari area madrasah hari ini.')
            ->query(
                IzinKeluar::query()
                    ->with(['siswa', 'pegawai'])
                    ->whereDate('tanggal', today())
                    ->latest()
            )
            ->columns([
                // 1. Siswa & Petugas (Digabung agar lebih lega)
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('primary')
                    ->weight(FontWeight::Bold)
                    ->description(fn(IzinKeluar $record): string => 'Pemberi Izin: ' . ($record->pegawai?->nama ?? '-'))
                    ->searchable(),

                // 2. Tujuan (Dibuat wrap agar tidak memotong kalimat)
                Tables\Columns\TextColumn::make('tujuan')
                    ->label('Keperluan / Tujuan')
                    ->icon('heroicon-m-map-pin')
                    ->color('gray')
                    ->wrap()
                    ->limit(40),

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
                        'keluar'  => 'Masih di Luar',
                        'kembali' => 'Sudah Kembali',
                        'pulang'  => 'Izin Pulang',
                        default   => '-',
                    }),

                // 4. Jam Keluar & Kembali (Digabung dalam 1 kolom Monospace)
                Tables\Columns\TextColumn::make('jam_keluar')
                    ->label('Waktu (Keluar - Kembali)')
                    ->icon('heroicon-m-clock')
                    ->fontFamily(FontFamily::Mono) // Font gaya digital/jam
                    ->alignEnd()
                    ->formatStateUsing(function (IzinKeluar $record): string {
                        $keluar = $record->jam_keluar ? Carbon::parse($record->jam_keluar)->format('H:i') : '--:--';
                        $kembali = $record->jam_kembali ? Carbon::parse($record->jam_kembali)->format('H:i') : '...';
                        return "{$keluar} - {$kembali}";
                    }),
            ])
            ->paginated(false)
            // Aksen zebra untuk kerapian membaca
            ->striped()
            // Tampilan menenangkan saat tidak ada siswa yang izin keluar
            ->emptyStateIcon('heroicon-o-shield-check')
            ->emptyStateHeading('Semua Siswa Terpantau Hadir')
            ->emptyStateDescription('Belum ada data siswa yang mengajukan izin keluar area sekolah pada hari ini.');
    }
}
