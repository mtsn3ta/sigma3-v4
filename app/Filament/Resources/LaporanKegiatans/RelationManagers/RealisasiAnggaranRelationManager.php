<?php

namespace App\Filament\Resources\LaporanKegiatans\RelationManagers;

use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RealisasiAnggaranRelationManager extends RelationManager
{
    protected static string $relationship = 'realisasiAnggaran';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('uraian')
                    ->disabled(),

                TextInput::make('qty')
                    ->numeric()
                    ->required(),

                TextInput::make('satuan')
                    ->disabled(),

                TextInput::make('harga_satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                FileUpload::make('bukti_nota')
                    ->label('Bukti Nota')
                    ->directory('laporan/bukti-nota'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('uraian')

            ->columns([

                TextColumn::make('uraian')
                    ->searchable(),

                TextColumn::make('qty')
                    ->alignCenter(),

                TextColumn::make('satuan')
                    ->alignCenter(),

                TextColumn::make('harga_satuan')
                    ->money('IDR')
                    ->alignEnd(),

                TextColumn::make('subtotal')
                    ->money('IDR')
                    ->weight('bold')
                    ->alignEnd(),

                TextColumn::make('bukti_nota')
                    ->label('Nota')
                    ->formatStateUsing(
                        fn($state) => $state ? '✔ Ada' : '-'
                    )
                    ->badge()
                    ->color(
                        fn($state) => $state ? 'success' : 'gray'
                    ),

            ])

            ->filters([
                //
            ])

            ->headerActions([
                //
            ])

            ->recordActions([

                EditAction::make(),

            ])

            ->toolbarActions([
                //
            ]);
    }
}
