<?php

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Activity Log')
                    ->schema([

                        TextEntry::make('created_at')
                            ->label('Waktu')
                            ->dateTime('d F Y H:i:s'),

                        TextEntry::make('user.name')
                            ->label('User')
                            ->placeholder('-'),

                        TextEntry::make('aksi')
                            ->label('Aksi')
                            ->badge(),

                        TextEntry::make('deskripsi')
                            ->label('Deskripsi')
                            ->columnSpanFull(),

                        TextEntry::make('ip_address')
                            ->label('IP Address'),

                        TextEntry::make('user_agent')
                            ->label('User Agent')
                            ->columnSpanFull(),

                    ])

                    ->columns(2),

            ]);
    }
}
