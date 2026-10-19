<?php

namespace App\Filament\Resources\LaporanKegiatans\Tables;

use App\Filament\Resources\LaporanKegiatans\LaporanKegiatanResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Illuminate\Support\Facades\Auth;
use App\Models\Ekstrakurikuler;

class LaporanKegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('proposal.nama_kegiatan')
                    ->label('Kegiatan')
                    ->weight('bold')
                    ->description(fn($record) => ($record->proposal?->jenis_kegiatan_label ?? '-') . ' • ' . ($record->proposal?->tempat ?? '-'))
                    ->wrap()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('proposal.ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('proposal.tanggal_mulai')
                    ->label('Pelaksanaan')
                    ->date('d M Y')
                    ->description(fn($record) => $record->proposal?->tanggal_selesai && $record->proposal->tanggal_selesai != $record->proposal->tanggal_mulai ? 's.d. ' . $record->proposal->tanggal_selesai->format('d M Y') : null)
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending_pembina' => 'warning',
                        'pending_waka' => 'info',
                        'pending_kepala' => 'primary',
                        'revision' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'draft' => 'Draft',
                        'pending_pembina' => 'Menunggu Pembina',
                        'pending_waka' => 'Menunggu Waka Kesiswaan',
                        'pending_kepala' => 'Menunggu Kepala Madrasah',
                        'revision' => 'Perlu Revisi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => ucfirst($state),
                    }),

                TextColumn::make('createdBy.pegawai.nama')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('ekstrakurikuler')
                    ->relationship('proposal.ekstrakurikuler', 'nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->preload(),

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
                        ->url(fn($record) => LaporanKegiatanResource::getUrl('preview', ['record' => $record])),

                    Action::make('edit')
                        ->label('Edit Laporan')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning')
                        ->visible(function ($record) {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            if (! $user) return false;

                            return in_array($record->status, ['draft', 'revision']) &&
                                ($user->isPelatihEkskul($record->proposal->ekstrakurikuler_id) || $user->isPembinaEkskul($record->proposal->ekstrakurikuler_id));
                        })
                        ->url(fn($record) => LaporanKegiatanResource::getUrl('edit', ['record' => $record])),

                    Action::make('pdf')
                        ->label('Download PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->visible(fn($record) => $record->status === 'approved')
                        ->url(fn($record) => route('laporan-kegiatan.pdf', $record))
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
