<?php

namespace App\Filament\Resources\ProposalKegiatans\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RabsRelationManager extends RelationManager
{
    protected static string $relationship = 'rabs';

    public function isReadOnly(): bool
    {
        return $this->ownerRecord->isLocked();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('uraian')
                    ->label('Uraian')
                    ->required()
                    ->maxLength(150)
                    ->columnSpanFull(),

                TextInput::make('qty')
                    ->label('Qty')
                    ->numeric()
                    ->default(1)
                    ->required(),

                TextInput::make('satuan')
                    ->label('Satuan')
                    ->default('Item')
                    ->required(),

                TextInput::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText(
                        'Subtotal dihitung otomatis saat data disimpan.'
                    ),

            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('uraian')

            ->columns([

                TextColumn::make('uraian')
                    ->label('Uraian')
                    ->searchable(),

                TextColumn::make('qty')
                    ->label('Qty')
                    ->sortable(),

                TextColumn::make('satuan')
                    ->label('Satuan'),

                TextColumn::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->money('IDR', divideBy: 1)
                    ->sortable(),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR', divideBy: 1)
                    ->sortable()
                    ->summarize([
                        Sum::make()
                            ->label('Total Anggaran')
                            ->money('IDR', divideBy: 1),
                    ]),

            ])

            ->filters([
                //
            ])

            ->headerActions([
                CreateAction::make(),
            ])

            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
