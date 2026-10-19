<?php

namespace App\Livewire;

use App\Models\UmpanBalikGuru;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class UmpanBalikGuruTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    #[On('refresh-umpan-balik-guru')]
    public function refreshTable(): void
    {
        //
    }

    protected function pegawaiId(): ?int
    {
        return Auth::user()?->pegawai?->id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                UmpanBalikGuru::query()
                    ->with(['user', 'referensi'])
                    ->where('pegawai_id', $this->pegawaiId())
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (string $state, UmpanBalikGuru $record): string => $record->labelKategori())
                    ->color(fn (string $state): string => match ($state) {
                        'perangkat' => 'success',
                        'jurnal'    => 'info',
                        'nilai'     => 'warning',
                        'kbm'       => 'primary',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Dari')
                    ->icon('heroicon-m-user-circle')
                    ->default('Kepala Madrasah'),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan / Evaluasi')
                    ->wrap()
                    ->limit(90)
                    ->description(fn (UmpanBalikGuru $record): ?string => $record->labelReferensi()),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('dibaca_pada')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state ? 'Sudah dibaca' : 'Belum dibaca')
                    ->color(fn ($state): string => $state ? 'success' : 'danger')
                    ->icon(fn ($state): string => $state ? 'heroicon-m-check-circle' : 'heroicon-m-envelope'),
            ])
            ->actions([
                Action::make('baca')
                    ->label('Baca')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->button()
                    ->modalHeading(fn (UmpanBalikGuru $record): string => 'Umpan Balik — ' . $record->labelKategori())
                    ->modalContent(fn (UmpanBalikGuru $record) => view('filament.modals.umpan-balik-detail', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->mountUsing(fn (UmpanBalikGuru $record) => $record->tandaiDibaca()),

                Action::make('tandaiDibaca')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->button()
                    ->visible(fn (UmpanBalikGuru $record): bool => $record->dibaca_pada === null)
                    ->action(function (UmpanBalikGuru $record): void {
                        $record->tandaiDibaca();

                        Notification::make()
                            ->title('Umpan balik ditandai sudah dibaca.')
                            ->success()
                            ->send();
                    }),
            ])
            ->headerActions([
                Action::make('tandaiSemuaDibaca')
                    ->label('Tandai Semua Dibaca')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(
                        fn (): bool => UmpanBalikGuru::query()
                            ->where('pegawai_id', $this->pegawaiId())
                            ->belumDibaca()
                            ->exists()
                    )
                    ->action(function (): void {
                        UmpanBalikGuru::query()
                            ->where('pegawai_id', $this->pegawaiId())
                            ->belumDibaca()
                            ->update(['dibaca_pada' => now()]);

                        Notification::make()
                            ->title('Semua umpan balik ditandai sudah dibaca.')
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Belum Ada Umpan Balik')
            ->emptyStateDescription('Catatan, evaluasi, dan saran dari Kepala Madrasah akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right')
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultSort('created_at', 'desc');
    }

    public function render(): View
    {
        return view('livewire.umpan-balik-guru-table');
    }
}
