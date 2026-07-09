<?php

namespace App\Filament\Widgets\Pelatih;

use App\Models\EkstrakurikulerPengampu;
use App\Models\Prestasi;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class PelatihPrestasiWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return \App\Support\ActiveRole::is('pelatih');
    }

    public function table(Table $table): Table
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pegawaiId = $user->pegawai?->id;

        $ekskulIds = EkstrakurikulerPengampu::where('pegawai_id', $pegawaiId)
            ->where('role', 'pelatih')
            ->pluck('ekstrakurikuler_id');

        return $table
            ->heading('Log Prestasi Atlet')
            ->description('Rekam jejak capaian atlet binaan.')
            ->query(
                Prestasi::query()
                    ->with('ekstrakurikuler')
                    ->whereIn('ekstrakurikuler_id', $ekskulIds)
                    ->latest('tanggal')
                    ->limit(8)
            )
            ->columns([
                // 1. Nama Lomba & Tanggal (Digabung)
                Tables\Columns\TextColumn::make('nama_lomba')
                    ->label('Kegiatan')
                    ->icon('heroicon-m-trophy')
                    ->iconColor('warning')
                    ->weight(FontWeight::Bold)
                    ->description(fn(Prestasi $record): string => Carbon::parse($record->tanggal)->format('d M Y'))
                    ->searchable(),

                // 2. Prestasi & Tingkat (Digabung)
                Tables\Columns\TextColumn::make('nama_prestasi')
                    ->label('Capaian')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-sparkles')
                    ->description(fn(Prestasi $record): string => strtoupper($record->tingkat))
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped()
            ->emptyStateIcon('heroicon-o-medal')
            ->emptyStateHeading('Belum Ada Prestasi')
            ->emptyStateDescription('Data prestasi akan muncul saat atlet meraih gelar juara.');
    }
}
