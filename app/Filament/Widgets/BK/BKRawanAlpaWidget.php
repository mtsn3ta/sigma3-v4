<?php

namespace App\Filament\Widgets\BK;

use App\Models\KehadiranSekolah;
use App\Models\Siswa;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class BKRawanAlpaWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    // Mengambil 1 kolom dari total 2 kolom default (agar tampil 50% / sejajar)
    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole('bk') ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Deteksi Dini (Rawan Alpa)')
            ->description('Siswa dengan akumulasi ketidakhadiran tanpa keterangan (≥ 3 Hari).')
            ->query(
                Siswa::query()
                    ->select('siswa.*')
                    ->selectSub(
                        KehadiranSekolah::selectRaw('COUNT(*)')
                            ->whereColumn('siswa_id', 'siswa.id')
                            ->where('status', 'alfa'),
                        'total_alpa'
                    )
                    ->having('total_alpa', '>=', 3)
                    ->orderByDesc('total_alpa')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->icon('heroicon-m-user-minus')
                    ->iconColor('danger')
                    ->weight(FontWeight::Bold)
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_alpa')
                    ->label('Jumlah Alpa')
                    ->badge()
                    ->icon('heroicon-m-x-circle')
                    ->formatStateUsing(fn(string $state): string => $state . ' Hari')
                    ->color(fn($state): string => match (true) {
                        $state >= 10 => 'danger',
                        $state >= 5  => 'warning',
                        default      => 'info',
                    })
                    ->sortable()
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped()
            // Empty state positif
            ->emptyStateIcon('heroicon-o-shield-check')
            ->emptyStateHeading('Kehadiran Terkendali')
            ->emptyStateDescription('Tidak ada siswa yang mencapai batas rawan alpa.');
    }
}
