<?php

namespace App\Livewire;

use App\Models\TugasGuruIzin;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RiwayatGuruBerhalanganTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    #[On('refresh-riwayat-guru-berhalangan')]
    public function refreshTable(): void
    {
        //
    }

    public function table(Table $table): Table
    {
        return $table

            ->query(

                TugasGuruIzin::query()

                    ->with([
                        'kelas',
                    ])

                    ->where(
                        'pegawai_id',
                        Auth::user()->pegawai->id
                    )

                    ->latest('tanggal')

                    ->latest('created_at')

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

                Tables\Columns\TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn(TugasGuruIzin $record) => match ($record->status) {
                        'baru' => 'warning',
                        'disampaikan' => 'info',
                        'selesai' => 'success',
                        default => 'gray',
                    }),

            ])

            ->filters([

                Tables\Filters\SelectFilter::make('kelas_id')
                    ->relationship(
                        'kelas',
                        'nama_kelas'
                    )
                    ->label('Kelas'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'baru' => 'Baru',
                        'disampaikan' => 'Sudah Disampaikan',
                        'selesai' => 'Selesai',
                    ]),

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

                    ->visible(fn(TugasGuruIzin $record) => $record->status === 'baru')

                    ->action(function (TugasGuruIzin $record) {

                        $this->dispatch(
                            'edit-guru-berhalangan',
                            id: $record->id
                        );
                    }),

                Action::make('delete')

                    ->label('Hapus')

                    ->icon('heroicon-o-trash')

                    ->color('danger')

                    ->requiresConfirmation()

                    ->modalHeading('Hapus Pengajuan')

                    ->modalDescription(
                        'Pengajuan guru berhalangan akan dihapus.'
                    )

                    ->visible(fn(TugasGuruIzin $record) => $record->status === 'baru')

                    ->action(function (TugasGuruIzin $record) {

                        $record->delete();

                        $this->dispatch(
                            'refresh-riwayat-guru-berhalangan'
                        );
                    }),

            ])

            ->emptyStateHeading(
                'Belum ada pengajuan guru berhalangan.'
            )

            ->striped()

            ->paginated([10, 25, 50]);
    }

    public function render(): View
    {
        return view(
            'livewire.riwayat-guru-berhalangan-table'
        );
    }
}
