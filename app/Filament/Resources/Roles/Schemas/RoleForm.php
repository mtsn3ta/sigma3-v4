<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Nama Role')
                    ->required()
                    ->columnSpanFull()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                CheckboxList::make('permissions')
                    ->relationship(
                        name: 'permissions',
                        titleAttribute: 'name'
                    )
                    ->columnSpanFull()
                    ->columns(3),

            ]);
    }
}
