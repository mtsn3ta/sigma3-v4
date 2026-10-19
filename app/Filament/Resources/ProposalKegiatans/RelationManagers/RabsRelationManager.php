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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RabsRelationManager extends RelationManager
{
    protected static string $relationship = 'rabs';

    protected static ?string $title = 'Rencana Anggaran Biaya (RAB)';

    public function isReadOnly(): bool
    {
        return $this->ownerRecord->isLocked();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('uraian')
                    ->label('Uraian Kebutuhan')
                    ->placeholder('Contoh: Transportasi PP Bus Mini, Konsumsi Peserta & Pendamping')
                    ->required()
                    ->maxLength(150)
                    ->columnSpanFull(),

                TextInput::make('qty')
                    ->label('Qty / Jumlah')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $qty = (float) ($get('qty') ?? 0);
                        $harga = (float) ($get('harga_satuan') ?? 0);
                        $set('subtotal', $qty * $harga);
                    })
                    ->required(),

                TextInput::make('satuan')
                    ->label('Satuan')
                    ->placeholder('Pcs, Orang, Dus, Unit')
                    ->default('Item')
                    ->required(),

                TextInput::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $qty = (float) ($get('qty') ?? 0);
                        $harga = (float) ($get('harga_satuan') ?? 0);
                        $set('subtotal', $qty * $harga);
                    })
                    ->required(),

                TextInput::make('subtotal')
                    ->label('Estimasi Subtotal')
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Otomatis dihitung dari Qty × Harga Satuan'),

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
                    ->weight('medium')
                    ->searchable(),

                TextColumn::make('qty')
                    ->label('Qty')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('satuan')
                    ->label('Satuan')
                    ->alignCenter(),

                TextColumn::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->money('IDR', divideBy: 1)
                    ->alignEnd()
                    ->sortable(),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR', divideBy: 1)
                    ->weight('bold')
                    ->color('success')
                    ->alignEnd()
                    ->sortable()
                    ->summarize([
                        Sum::make()
                            ->label('Total RAB')
                            ->money('IDR', divideBy: 1),
                    ]),

            ])

            ->filters([
                //
            ])

            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Item Anggaran')
                    ->icon('heroicon-o-plus')
                    ->modalWidth('xl'),
            ])

            ->recordActions([
                EditAction::make()
                    ->modalWidth('xl'),
                DeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
