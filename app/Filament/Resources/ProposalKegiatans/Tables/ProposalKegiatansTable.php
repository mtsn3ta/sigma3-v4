<?php

namespace App\Filament\Resources\ProposalKegiatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Filament\Resources\ProposalKegiatans\ProposalKegiatanResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Illuminate\Support\Facades\Auth;
use App\Models\Ekstrakurikuler;

class ProposalKegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->weight('bold')
                    ->description(fn($record) => ($record->jenis_kegiatan_label ?? '-') . ' • ' . ($record->tempat ?? '-'))
                    ->wrap()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_anggaran')
                    ->label('Anggaran')
                    ->money('IDR', divideBy: 1)
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('tanggal_mulai')
                    ->label('Pelaksanaan')
                    ->date('d M Y')
                    ->description(fn($record) => $record->tanggal_selesai && $record->tanggal_selesai != $record->tanggal_mulai ? 's.d. ' . $record->tanggal_selesai->format('d M Y') : null)
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
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->defaultSort('created_at', 'desc')

            ->filters([
                SelectFilter::make('ekstrakurikuler_id')
                    ->label('Ekstrakurikuler')
                    ->options(fn() => Ekstrakurikuler::pluck('nama', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Status Approval')
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
                        ->label('Preview Dokumen')
                        ->icon('heroicon-o-eye')
                        ->color('primary')
                        ->url(fn($record) => ProposalKegiatanResource::getUrl('preview', ['record' => $record])),

                    Action::make('edit')
                        ->label('Edit Proposal')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning')
                        ->visible(function ($record) {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            if (! $user) return false;

                            return in_array($record->status, ['draft', 'revision']) &&
                                ($user->isPelatihEkskul($record->ekstrakurikuler_id) || $user->isPembinaEkskul($record->ekstrakurikuler_id));
                        })
                        ->url(fn($record) => ProposalKegiatanResource::getUrl('edit', ['record' => $record])),

                    Action::make('downloadPdf')
                        ->label('Download PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->visible(fn($record) => $record->status === 'approved')
                        ->url(fn($record) => route('proposal.pdf', $record))
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
