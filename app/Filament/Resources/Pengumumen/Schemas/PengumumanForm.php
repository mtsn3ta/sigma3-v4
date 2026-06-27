<?php

namespace App\Filament\Resources\Pengumumen\Schemas;

use App\Models\Ekstrakurikuler;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PengumumanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Pengumuman')

                    ->schema([

                        Select::make('ekstrakurikuler_id')

                            ->label('Ekstrakurikuler')

                            ->searchable()

                            ->preload()

                            ->native(false)

                            ->placeholder('Pengumuman Sekolah')

                            ->options(function () {

                                /** @var User|null $user */
                                $user = Auth::user();

                                if (! $user) {
                                    return [];
                                }

                                if (
                                    $user->hasRole('super_admin')
                                    || $user->hasRole('waka_kesiswaan')
                                    || $user->hasRole('kepala_madrasah')
                                ) {

                                    return Ekstrakurikuler::query()

                                        ->orderBy('nama')

                                        ->pluck('nama', 'id');
                                }

                                return Ekstrakurikuler::query()

                                    ->whereIn(
                                        'id',
                                        $user->pegawai
                                            ? $user->pegawai
                                            ->pengampus()
                                            ->pluck('ekstrakurikuler_id')
                                            : []
                                    )

                                    ->orderBy('nama')

                                    ->pluck('nama', 'id');
                            })

                            ->default(function () {

                                /** @var User|null $user */
                                $user = Auth::user();

                                if (! $user) {
                                    return null;
                                }

                                if (
                                    $user->hasRole('super_admin')
                                    || $user->hasRole('waka_kesiswaan')
                                    || $user->hasRole('kepala_madrasah')
                                ) {
                                    return null;
                                }

                                $ids = $user->pegawai
                                    ? $user->pegawai
                                    ->pengampus()
                                    ->pluck('ekstrakurikuler_id')
                                    : collect();

                                return $ids->count() === 1
                                    ? $ids->first()
                                    : null;
                            })

                            ->disabled(function () {

                                /** @var User|null $user */
                                $user = Auth::user();

                                if (! $user) {
                                    return false;
                                }

                                if (
                                    $user->hasRole('super_admin')
                                    || $user->hasRole('waka_kesiswaan')
                                    || $user->hasRole('kepala_madrasah')
                                ) {
                                    return false;
                                }

                                $ids = $user->pegawai
                                    ? $user->pegawai
                                    ->pengampus()
                                    ->pluck('ekstrakurikuler_id')
                                    : collect();

                                return $ids->count() === 1;
                            }),

                        TextInput::make('judul')

                            ->label('Judul')

                            ->placeholder('Contoh: Latihan Hari Jumat Diliburkan')

                            ->required()

                            ->maxLength(150),

                        Select::make('tipe')

                            ->label('Tipe Alert')

                            ->options([

                                'info' => 'Info',

                                'success' => 'Success',

                                'warning' => 'Warning',

                                'danger' => 'Danger',

                            ])

                            ->default('info')

                            ->native(false)

                            ->required(),

                        Select::make('status')

                            ->label('Status')

                            ->options([

                                'draft' => 'Draft',

                                'publish' => 'Publish',

                                'arsip' => 'Arsip',

                            ])

                            ->default('draft')

                            ->native(false)

                            ->required(),

                        Toggle::make('is_pinned')

                            ->label('Tampilkan sebagai Urgent Alert')

                            ->helperText(
                                'Jika aktif maka pengumuman diprioritaskan tampil di banner atas website.'
                            ),

                        DatePicker::make('tanggal_mulai')

                            ->required()

                            ->default(today()),

                        DatePicker::make('tanggal_selesai')

                            ->required()

                            ->default(today()->addDays(7)),

                    ])

                    ->columns(2),

                Section::make('Isi Pengumuman')

                    ->schema([

                        RichEditor::make('isi')

                            ->required()

                            ->columnSpanFull(),

                        FileUpload::make('lampiran')

                            ->label('Lampiran')

                            ->disk('public')

                            ->directory('pengumuman')

                            ->downloadable()

                            ->openable()

                            ->columnSpanFull(),

                    ])

                    ->columnSpanFull(),

            ]);
    }
}
