<?php

namespace App\Livewire;

use App\Filament\Actions\BeriUmpanBalikAction;
use App\Models\JurnalMengajar;
use App\Models\Pegawai;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MonitoringJurnalGuruTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    protected function periode(): array
    {
        $state = $this->getTableFilterState('periode') ?? [];

        $dari = ! empty($state['dari'])
            ? Carbon::parse($state['dari'], 'Asia/Jakarta')->startOfDay()
            : now('Asia/Jakarta')->startOfMonth();

        $sampai = ! empty($state['sampai'])
            ? Carbon::parse($state['sampai'], 'Asia/Jakarta')->endOfDay()
            : now('Asia/Jakarta')->endOfMonth();

        return [$dari, $sampai];
    }

    public function table(Table $table): Table
    {
        [$dari, $sampai] = $this->periode();

        return $table
            ->query(
                JurnalMengajar::query()
                    ->with(['pegawai', 'kelas'])
                    ->withCount('umpanBalik')
                    ->whereBetween('created_at', [$dari, $sampai])
            )
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->icon('heroicon-m-calendar-days')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pegawai.nama')
                    ->label('Guru')
                    ->icon('heroicon-m-user-circle')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jam_ke')
                    ->label('Jam')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn ($state): string => 'Jam ke-' . $state)
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('mapel')
                    ->label('Mapel & Materi')
                    ->searchable()
                    ->description(fn (JurnalMengajar $record): string => str($record->materi)->limit(50))
                    ->wrap(),

                Tables\Columns\ImageColumn::make('dokumentasi_foto')
                    ->label('Dok. KBM')
                    ->disk('public')
                    ->square()
                    ->size(44),

                Tables\Columns\TextColumn::make('umpan_balik_count')
                    ->label('Umpan Balik')
                    ->badge()
                    ->alignCenter()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'gray')
                    ->icon('heroicon-m-chat-bubble-left-right'),
            ])
            ->filters([
                Tables\Filters\Filter::make('periode')
                    ->label('Periode')
                    ->form([
                        DatePicker::make('dari')
                            ->label('Dari Tanggal')
                            ->default(now('Asia/Jakarta')->startOfMonth()->toDateString())
                            ->native(false),
                        DatePicker::make('sampai')
                            ->label('Sampai Tanggal')
                            ->default(now('Asia/Jakarta')->endOfMonth()->toDateString())
                            ->native(false),
                    ])
                    ->indicateUsing(function (array $data): array {
                        $indikator = [];

                        if ($data['dari'] ?? null) {
                            $indikator[] = 'Dari: ' . Carbon::parse($data['dari'])->translatedFormat('d M Y');
                        }

                        if ($data['sampai'] ?? null) {
                            $indikator[] = 'Sampai: ' . Carbon::parse($data['sampai'])->translatedFormat('d M Y');
                        }

                        return $indikator;
                    }),

                Tables\Filters\SelectFilter::make('pegawai_id')
                    ->label('Guru')
                    ->relationship('pegawai', 'nama')
                    ->searchable()
                    ->preload()
                    ->native(false),

                Tables\Filters\Filter::make('ada_dokumentasi')
                    ->label('Hanya yang Ada Dokumentasi')
                    ->toggle()
                    ->query(fn ($query) => $query->where(function ($query) {
                        $query->whereNotNull('dokumentasi_foto')
                            ->orWhereNotNull('dokumentasi_video');
                    })),
            ])
            ->filtersFormColumns(3)
            ->recordActions([
                Action::make('dokumentasi')
                    ->label('Dokumentasi')
                    ->icon('heroicon-o-camera')
                    ->color('info')
                    ->button()
                    ->visible(fn (JurnalMengajar $record): bool => (bool) ($record->dokumentasi_foto || $record->dokumentasi_video))
                    ->modalHeading(fn (JurnalMengajar $record): string => 'Dokumentasi KBM — ' . $record->kelas?->nama_kelas)
                    ->modalContent(fn (JurnalMengajar $record) => view('filament.modals.jurnal-dokumentasi', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                BeriUmpanBalikAction::make('jurnal'),
            ])
            ->emptyStateHeading('Belum Ada Jurnal')
            ->emptyStateDescription('Jurnal mengajar guru pada periode ini belum tersedia.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->defaultSort('tanggal', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }

    public function render(): View
    {
        return view('livewire.monitoring-jurnal-guru-table');
    }
}
