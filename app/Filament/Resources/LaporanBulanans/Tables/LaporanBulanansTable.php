<?php

namespace App\Filament\Resources\LaporanBulanans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Filament\Resources\LaporanBulanans\LaporanBulananResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Illuminate\Support\Facades\Auth;
use App\Models\Ekstrakurikuler;

class LaporanBulanansTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->weight('bold')
                    ->description(function ($record) {
                        $bulanNama = \Carbon\Carbon::create()->month((int) $record->bulan)->translatedFormat('F');
                        return "Periode: {$bulanNama} {$record->tahun}";
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bulan')
                    ->label('Bulan')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::create()->month((int) $state)->translatedFormat('F'))
                    ->sortable(),

                TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable(),

                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn($record) => match ($record->status) {
                        'draft' => 'gray',
                        'pending_pembina' => 'warning',
                        'pending_waka' => 'info',
                        'pending_kepala' => 'primary',
                        'revision' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('createdBy.pegawai.nama')
                    ->label('Dibuat Oleh')
                    ->placeholder('-')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->defaultSort('tahun', 'desc')

            ->filters([
                SelectFilter::make('ekstrakurikuler_id')
                    ->label('Ekstrakurikuler')
                    ->options(fn() => Ekstrakurikuler::pluck('nama', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options([
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending_pembina' => 'Menunggu Pembina',
                        'pending_waka' => 'Menunggu Waka Kesiswaan',
                        'pending_kepala' => 'Menunggu Kepala Madrasah',
                        'approved' => 'Disetujui',
                        'revision' => 'Perlu Revisi',
                        'rejected' => 'Ditolak',
                    ]),
            ])

            ->recordActions([
                ActionGroup::make([
                    Action::make('preview')
                        ->label('Preview Laporan')
                        ->icon('heroicon-o-eye')
                        ->color('primary')
                        ->url(fn($record) => LaporanBulananResource::getUrl('preview', ['record' => $record])),

                    Action::make('edit')
                        ->label('Edit Laporan')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning')
                        ->visible(function ($record) {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            if (! $user) return false;

                            return in_array($record->status, ['draft', 'revision']) &&
                                ($user->isPelatihEkskul($record->ekstrakurikuler_id) || $user->isPembinaEkskul($record->ekstrakurikuler_id));
                        })
                        ->url(fn($record) => LaporanBulananResource::getUrl('edit', ['record' => $record])),

                    Action::make('pdf')
                        ->label('Download PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->visible(fn($record) => $record->status === 'approved')
                        ->url(fn($record) => route('laporan-bulanan.pdf', $record))
                        ->openUrlInNewTab(),
                ])
                ->tooltip('Menu Aksi')
                ->icon('heroicon-m-ellipsis-vertical'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
