<?php

namespace App\Filament\Widgets\Guru;

use App\Filament\Pages\UmpanBalik as UmpanBalikPage;
use App\Models\UmpanBalikGuru;
use App\Support\ActiveRole;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class GuruUmpanBalikWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return ActiveRole::is('guru');
    }

    public function table(Table $table): Table
    {
        $pegawaiId = Auth::user()?->pegawai?->id;

        return $table
            ->heading('Umpan Balik Kepala Madrasah')
            ->description('Catatan dan evaluasi terbaru untuk Anda.')
            ->query(
                UmpanBalikGuru::query()
                    ->with('referensi')
                    ->where('pegawai_id', $pegawaiId)
                    ->latest()
                    ->limit(5)
            )
            ->headerActions([
                Action::make('lihat_semua')
                    ->label('Lihat Semua')
                    ->icon('heroicon-m-arrow-right-circle')
                    ->color('primary')
                    ->url(fn(): string => UmpanBalikPage::getUrl()),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn(string $state, UmpanBalikGuru $record): string => $record->labelKategori())
                    ->color(fn(string $state): string => match ($state) {
                        'perangkat' => 'success',
                        'jurnal'    => 'info',
                        'nilai'     => 'warning',
                        'kbm'       => 'primary',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->wrap()
                    ->limit(45)
                    ->description(fn(UmpanBalikGuru $record): ?string => $record->labelReferensi()),

                Tables\Columns\TextColumn::make('dibaca_pada')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state): string => $state ? 'Dibaca' : 'Baru')
                    ->color(fn($state): string => $state ? 'success' : 'danger'),
            ])
            ->actions([
                Action::make('buka')
                    ->label('Buka')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->iconButton()
                    ->tooltip('Buka halaman Umpan Balik')
                    ->url(fn(): string => UmpanBalikPage::getUrl()),
            ])
            ->paginated(false)
            ->striped()
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right')
            ->emptyStateHeading('Belum Ada Umpan Balik')
            ->emptyStateDescription('Catatan dari Kepala Madrasah akan muncul di sini.');
    }
}
