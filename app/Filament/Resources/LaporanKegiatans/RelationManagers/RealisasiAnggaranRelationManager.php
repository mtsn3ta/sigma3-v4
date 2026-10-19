<?php

namespace App\Filament\Resources\LaporanKegiatans\RelationManagers;

use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RealisasiAnggaranRelationManager extends RelationManager
{
    protected static string $relationship = 'realisasiAnggaran';

    protected static ?string $title = 'Realisasi Anggaran Biaya';

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
                    ->disabled()
                    ->columnSpanFull(),

                TextInput::make('qty')
                    ->label('Qty Realisasi')
                    ->numeric()
                    ->minValue(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $qty = (float) ($get('qty') ?? 0);
                        $harga = (float) ($get('harga_satuan') ?? 0);
                        $set('subtotal', $qty * $harga);
                    })
                    ->required(),

                TextInput::make('satuan')
                    ->label('Satuan')
                    ->disabled(),

                TextInput::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->prefix('Rp')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $qty = (float) ($get('qty') ?? 0);
                        $harga = (float) ($get('harga_satuan') ?? 0);
                        $set('subtotal', $qty * $harga);
                    })
                    ->required(),

                TextInput::make('subtotal')
                    ->label('Subtotal Realisasi')
                    ->numeric()
                    ->prefix('Rp')
                    ->readOnly()
                    ->dehydrated(),

                FileUpload::make('bukti')
                    ->label('Bukti Nota / Struk Transaksi')
                    ->helperText('Upload foto nota, faktur, atau kuitansi pembayaran')
                    ->disk('public')
                    ->directory('laporan/bukti')
                    ->image()
                    ->imageEditor()
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('uraian')

            ->columns([

                TextColumn::make('uraian')
                    ->label('Uraian Kebutuhan')
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('qty')
                    ->label('Qty')
                    ->alignCenter(),

                TextColumn::make('satuan')
                    ->label('Satuan')
                    ->alignCenter()
                    ->color('gray'),

                TextColumn::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->money('IDR')
                    ->alignEnd(),

                TextColumn::make('subtotal')
                    ->label('Subtotal Realisasi')
                    ->money('IDR')
                    ->weight('bold')
                    ->color('success')
                    ->alignEnd()
                    ->summarize(
                        Sum::make()
                            ->label('Total Realisasi')
                            ->money('IDR')
                    ),

                TextColumn::make('bukti')
                    ->label('Nota')
                    ->formatStateUsing(
                        fn($state) => $state ? '✔ Ada Nota' : 'Belum Ada'
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

                EditAction::make()
                    ->modalWidth('lg')
                    ->modalHeading('Edit Realisasi Anggaran'),

            ])

            ->toolbarActions([
                //
            ]);
    }
}
