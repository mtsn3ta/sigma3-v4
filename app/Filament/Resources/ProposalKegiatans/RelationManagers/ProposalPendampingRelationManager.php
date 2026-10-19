<?php

namespace App\Filament\Resources\ProposalKegiatans\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use App\Models\Pegawai;
use App\Models\EkstrakurikulerPengampu;
use App\Models\ProposalPendamping;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class ProposalPendampingRelationManager extends RelationManager
{
    protected static string $relationship = 'pendamping';

    protected static ?string $title = 'Pendamping';

    public function isReadOnly(): bool
    {
        return $this->ownerRecord->isLocked();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('pegawai_id')
                    ->label('Pegawai')
                    ->options(
                        Pegawai::query()
                            ->orderBy('nama')
                            ->pluck('nama', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('peran')
                    ->label('Peran')
                    ->options([
                        'pelatih' => 'Pelatih',
                        'pembina' => 'Pembina',
                        'guru_pendamping' => 'Guru Pendamping',
                        'official' => 'Official',
                        'medis' => 'Medis',
                        'transportasi' => 'Transportasi',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')

            ->columns([

                TextColumn::make('pegawai.nama')
                    ->label('Nama Pendamping')
                    ->weight('medium')
                    ->searchable(),

                TextColumn::make('pegawai.nip')
                    ->label('NIP')
                    ->placeholder('-'),

                TextColumn::make('peran')
                    ->label('Peran')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pembina' => 'success',
                        'pelatih' => 'info',
                        'guru_pendamping' => 'primary',
                        'medis' => 'danger',
                        'transportasi' => 'warning',
                        'official' => 'secondary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'guru_pendamping' => 'Guru Pendamping',
                        default => ucfirst($state),
                    }),

            ])

            ->filters([
                //
            ])

            ->headerActions([

                CreateAction::make()
                    ->label('Tambah Pendamping')
                    ->icon('heroicon-o-user-plus')
                    ->modalWidth('lg')
                    ->visible(fn() => ! $this->ownerRecord->isLocked()),

                Action::make('ambilPengampu')
                    ->label('Ambil dari Pengampu Ekskul')
                    ->icon('heroicon-o-users')
                    ->color('success')
                    ->visible(fn() => ! $this->ownerRecord->isLocked())
                    ->requiresConfirmation()
                    ->modalHeading('Sinkronisasi Pengampu Ekskul')
                    ->modalDescription('Apakah Anda yakin ingin menambahkan semua pelatih dan pembina resmi dari ekstrakurikuler ini ke daftar pendamping?')
                    ->action(function () {

                        $proposal = $this->ownerRecord;

                        $pengampus = EkstrakurikulerPengampu::query()
                            ->where(
                                'ekstrakurikuler_id',
                                $proposal->ekstrakurikuler_id
                            )
                            ->get();

                        foreach ($pengampus as $pengampu) {

                            $existing = ProposalPendamping::where(
                                'proposal_kegiatan_id',
                                $proposal->id
                            )
                                ->where(
                                    'pegawai_id',
                                    $pengampu->pegawai_id
                                )
                                ->first();

                            if ($existing) {

                                if (
                                    $existing->peran === 'pelatih'
                                    && $pengampu->role === 'pembina'
                                ) {

                                    $existing->update([
                                        'peran' => 'pembina',
                                    ]);
                                }

                                continue;
                            }

                            ProposalPendamping::create([
                                'proposal_kegiatan_id' => $proposal->id,
                                'pegawai_id' => $pengampu->pegawai_id,
                                'peran' => $pengampu->role,
                            ]);
                        }

                        Notification::make()
                            ->success()
                            ->title('Pengampu berhasil ditambahkan.')
                            ->send();
                    }),

            ])

            ->recordActions([

                DeleteAction::make()
                    ->visible(fn() => ! $this->ownerRecord->isLocked()),

            ])

            ->toolbarActions([

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ])
                    ->visible(fn() => ! $this->ownerRecord->isLocked()),

            ]);
    }
}
