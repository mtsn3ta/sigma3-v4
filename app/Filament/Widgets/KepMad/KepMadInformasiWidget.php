<?php

namespace App\Filament\Widgets\KepMad;

use App\Models\Prestasi;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class KepMadInformasiWidget extends BaseWidget
{
    protected static ?int $sort = 5;

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
            ->heading('Prestasi & Penghargaan Terbaru')
            ->description('Pantau pencapaian dan kebanggaan terbaru yang diraih oleh siswa madrasah.')
            ->query(
                Prestasi::query()
                    ->with('ekstrakurikuler')
                    ->latest('tanggal')
                    ->limit(5)
            )
            ->columns([
                // 1. Nama Lomba & Ekstrakurikuler (Digabung agar lebih lega)
                Tables\Columns\TextColumn::make('nama_lomba')
                    ->label('Kompetisi / Lomba')
                    ->icon('heroicon-m-trophy')
                    ->iconColor('warning') // Warna Emas/Kuning
                    ->weight(FontWeight::Bold)
                    ->description(
                        fn(Prestasi $record): string => $record->ekstrakurikuler
                            ? 'Divisi: ' . $record->ekstrakurikuler->nama
                            : 'Kategori: Mandiri/Umum'
                    )
                    ->searchable(),

                // 2. Pencapaian / Prestasi
                Tables\Columns\TextColumn::make('nama_prestasi')
                    ->label('Pencapaian')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-star'),

                // 3. Tingkat
                Tables\Columns\TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-globe-asia-australia')
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                // 4. Tanggal (Dipindah ke ujung dengan time-ago)
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal Diraih')
                    ->date('d M Y')
                    ->icon('heroicon-m-calendar-days')
                    ->description(fn(Prestasi $record): string => \Carbon\Carbon::parse($record->tanggal)->diffForHumans())
                    ->sortable()
                    ->alignEnd(),
            ])
            ->paginated(false)
            // Aksen zebra untuk memudahkan eksekutif membaca baris data
            ->striped()
            // Tampilan khusus saat belum ada data prestasi
            ->emptyStateIcon('heroicon-o-sparkles')
            ->emptyStateHeading('Belum Ada Prestasi Baru')
            ->emptyStateDescription('Data penghargaan dan prestasi siswa akan muncul di sini secara otomatis.');
    }
}
