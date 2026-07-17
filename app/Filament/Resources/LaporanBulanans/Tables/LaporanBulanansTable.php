<?php

namespace App\Filament\Resources\LaporanBulanans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\LaporanBulanans\LaporanBulananResource;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class LaporanBulanansTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bulan')
                    ->badge(),

                TextColumn::make('tahun')
                    ->sortable(),

                TextColumn::make('createdBy.pegawai.nama')
                    ->label('Dibuat Oleh')
                    ->placeholder('-'),

                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn($record) => match ($record->status) {

                        'draft' => 'gray',

                        'pending_pembina' => 'warning',

                        'pending_waka' => 'warning',

                        'pending_kepala' => 'warning',

                        'revision' => 'warning',

                        'approved' => 'success',

                        'rejected' => 'danger',

                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->defaultSort('tahun', 'desc')

            ->filters([])

            ->recordActions([
                Action::make('open')

                    ->visible(function ($record) {

                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();

                        if (! $user) {
                            return false;
                        }

                        // Pelatih & Pembina boleh membuka draft/revision
                        if (
                            in_array($record->status, ['draft', 'revision']) &&
                            (
                                $user->isPelatihEkskul($record->ekstrakurikuler_id)
                                || $user->isPembinaEkskul($record->ekstrakurikuler_id)
                            )
                        ) {
                            return true;
                        }

                        // Setelah submit semua approver boleh melihat preview
                        return ! in_array($record->status, ['draft', 'revision']);
                    })

                    ->label(function ($record) {

                        return in_array($record->status, ['draft', 'revision'])
                            ? 'Edit'
                            : 'Preview';
                    })

                    ->icon(function ($record) {

                        return in_array($record->status, ['draft', 'revision'])
                            ? 'heroicon-o-pencil-square'
                            : 'heroicon-o-eye';
                    })

                    ->url(function ($record) {

                        return in_array($record->status, ['draft', 'revision'])
                            ? LaporanBulananResource::getUrl('edit', [
                                'record' => $record,
                            ])
                            : LaporanBulananResource::getUrl('preview', [
                                'record' => $record,
                            ]);
                    }),
            ])

            ->toolbarActions([

                BulkActionGroup::make([

                    DeleteBulkAction::make(),

                ]),

            ]);
    }
}
