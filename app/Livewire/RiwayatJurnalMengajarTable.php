<?php

namespace App\Livewire;

use App\Models\JurnalMengajar;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Actions\Action;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class RiwayatJurnalMengajarTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    #[On('refresh-riwayat-jurnal')]
    public function refreshTable(): void
    {
        // sengaja kosong
    }
    public function table(Table $table): Table
    {
        return $table

            ->query(

                JurnalMengajar::query()

                    ->with([
                        'kelas',
                        'kehadiranPembelajaran',
                    ])

                    ->where(
                        'pegawai_id',
                        Auth::user()->pegawai->id
                    )

                    ->latest('tanggal')

                    ->latest('jam_ke')

            )

            ->columns([

                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('mapel')
                    ->label('Mapel')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jam_ke')
                    ->label('Jam'),

                Tables\Columns\TextColumn::make('kehadiran_pembelajaran_count')
                    ->label('Absen')
                    ->counts('kehadiranPembelajaran')
                    ->badge()
                    ->alignCenter(),

            ])

            ->filters([

                Tables\Filters\SelectFilter::make('kelas_id')
                    ->relationship(
                        'kelas',
                        'nama_kelas'
                    )
                    ->label('Kelas'),

                Tables\Filters\Filter::make('tanggal')
                    ->form([

                        \Filament\Forms\Components\DatePicker::make('tanggal')

                    ])
                    ->query(function ($query, array $data) {

                        return $query->when(

                            $data['tanggal'],

                            fn($query, $tanggal) =>

                            $query->whereDate(
                                'tanggal',
                                $tanggal
                            )

                        );
                    }),

            ])

            ->actions([

                Action::make('edit')

                    ->label('Edit')

                    ->icon('heroicon-o-pencil-square')

                    ->color('warning')

                    ->button()

                    ->action(function (JurnalMengajar $record) {

                        $this->dispatch(
                            'edit-jurnal-mengajar',
                            id: $record->id
                        );
                    }),

                Action::make('delete')

                    ->label('Hapus')

                    ->icon('heroicon-o-trash')

                    ->color('danger')

                    ->requiresConfirmation()

                    ->modalHeading('Hapus Jurnal Mengajar')

                    ->modalDescription(
                        'Jurnal mengajar beserta data ketidakhadiran pembelajaran akan dihapus.'
                    )

                    ->action(function (JurnalMengajar $record) {

                        $record->delete();

                        $this->dispatch('refresh-riwayat-jurnal');
                    }),

            ])

            ->emptyStateHeading(
                'Belum ada jurnal mengajar.'
            )

            ->striped()

            ->paginated([10, 25, 50]);
    }

    public function render(): View
    {
        return view(
            'livewire.riwayat-jurnal-mengajar-table'
        );
    }
}
