<?php

namespace App\Filament\Resources\Galeris\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\Action;

class FotoRelationManager extends RelationManager
{
    protected static string $relationship = 'foto';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('foto')
                    ->label('Foto')
                    ->image()
                    ->disk('public')
                    ->directory('galeri')
                    ->required(),

                TextInput::make('urutan')
                    ->numeric()
                    ->default(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('judul')
            ->columns([

                ImageColumn::make('foto')

                    ->disk('public')

                    ->square()

                    ->label('Foto'),

                TextColumn::make('urutan')

                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([

                Action::make('upload')

                    ->label('Upload Foto')

                    ->icon('heroicon-o-photo')

                    ->form([

                        FileUpload::make('foto')

                            ->multiple()

                            ->reorderable()

                            ->image()

                            ->disk('public')

                            ->directory('galeri')

                            ->required(),

                    ])

                    ->action(function (array $data) {

                        $lastOrder = $this->ownerRecord
                            ->foto()
                            ->max('urutan') ?? 0;

                        foreach ($data['foto'] as $index => $foto) {

                            $this->ownerRecord
                                ->foto()
                                ->create([

                                    'foto' => $foto,

                                    'urutan' => $lastOrder + $index + 1,

                                ]);
                        }
                    }),

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
