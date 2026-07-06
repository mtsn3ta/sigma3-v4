<?php

namespace App\Filament\Widgets\BK;

use App\Models\PermasalahanSiswa;
use App\Models\Siswa;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class BKRankingPoinWidget extends BaseWidget
{
    protected static ?int $sort = 2;

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
            ->heading('Akumulasi Poin Pelanggaran')
            ->description('Top 10 siswa dengan rekam jejak poin kedisiplinan tertinggi.')
            ->query(
                Siswa::query()
                    ->select('siswa.*')
                    ->selectSub(
                        PermasalahanSiswa::selectRaw('COALESCE(SUM(poin), 0)')
                            ->whereColumn('siswa_id', 'siswa.id'),
                        'total_poin'
                    )
                    ->having('total_poin', '>', 0)
                    ->orderByDesc('total_poin')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('warning')
                    ->weight(FontWeight::Bold)
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_poin')
                    ->label('Total Poin')
                    ->badge()
                    ->icon('heroicon-m-shield-exclamation')
                    ->formatStateUsing(fn(string $state): string => $state . ' Pts')
                    ->color(fn($state): string => match (true) {
                        $state >= 100 => 'danger',
                        $state >= 50  => 'warning',
                        default       => 'info',
                    })
                    ->sortable()
                    ->alignEnd(),
            ])
            ->paginated(false)
            ->striped()
            // Empty state positif
            ->emptyStateIcon('heroicon-o-check-badge')
            ->emptyStateHeading('Disiplin Terjaga')
            ->emptyStateDescription('Belum ada siswa yang tercatat memiliki poin pelanggaran.');
    }
}
