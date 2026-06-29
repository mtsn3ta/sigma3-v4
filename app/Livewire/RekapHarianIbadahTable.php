<?php

namespace App\Livewire;

use App\Models\Kelas;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class RekapHarianIbadahTable extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'tanggal' => now()->toDateString(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema

            ->components([

                DatePicker::make('tanggal')

                    ->label('Tanggal Rekap')

                    ->default(now())

                    ->live()

                    ->afterStateUpdated(
                        fn() => $this->resetTable()
                    ),

            ])

            ->statePath('data');
    }

    protected function getTanggal(): string
    {
        return $this->data['tanggal']
            ?? now()->toDateString();
    }

    protected function countIbadah(
        string $jenis,
        string $status,
    ): \Closure {

        return function (Builder $query) use (
            $jenis,
            $status
        ) {

            $query->whereHas(

                'kehadiranIbadah',

                fn(Builder $query) =>

                $query

                    ->whereDate(
                        'tanggal',
                        $this->getTanggal()
                    )

                    ->where(
                        'jenis',
                        $jenis
                    )

                    ->where(
                        'status',
                        $status
                    )

            );
        };
    }

    public function table(Table $table): Table
    {
        return $table

            ->heading('Rekap Kehadiran Ibadah')

            ->description(

                fn() =>

                'Tanggal : '

                    . Carbon::parse(
                        $this->getTanggal()
                    )->translatedFormat('d F Y')

            )

            ->query(

                Kelas::query()

                    ->withCount([

                        'siswa as dhuha_hadir' =>
                        $this->countIbadah(
                            'dhuha',
                            'hadir'
                        ),

                        'siswa as dhuha_menstruasi' =>
                        $this->countIbadah(
                            'dhuha',
                            'menstruasi'
                        ),

                        'siswa as dhuha_tidak_hadir' =>
                        $this->countIbadah(
                            'dhuha',
                            'tidak_hadir'
                        ),

                        'siswa as dzuhur_hadir' =>
                        $this->countIbadah(
                            'dzuhur',
                            'hadir'
                        ),

                        'siswa as dzuhur_menstruasi' =>
                        $this->countIbadah(
                            'dzuhur',
                            'menstruasi'
                        ),

                        'siswa as dzuhur_tidak_hadir' =>
                        $this->countIbadah(
                            'dzuhur',
                            'tidak_hadir'
                        ),

                        'siswa as total_siswa',

                    ])

            )

            ->columns([

                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Kelas')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('dhuha_hadir')
                    ->label('Dhuha ✓')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('dhuha_menstruasi')
                    ->label('Dhuha M')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('dhuha_tidak_hadir')
                    ->label('Dhuha ×')
                    ->badge()
                    ->color('danger'),

                Tables\Columns\TextColumn::make('dzuhur_hadir')
                    ->label('Dzuhur ✓')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('dzuhur_menstruasi')
                    ->label('Dzuhur M')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('dzuhur_tidak_hadir')
                    ->label('Dzuhur ×')
                    ->badge()
                    ->color('danger'),

                Tables\Columns\TextColumn::make('total_siswa')
                    ->label('Total')
                    ->weight('bold'),

            ])

            ->actions([

                Action::make('detail')

                    ->label('Detail')

                    ->icon('heroicon-o-eye')

                    ->modalHeading(
                        fn(Kelas $record) =>
                        'Detail Kehadiran Ibadah ' . $record->nama_kelas
                    )

                    ->modalContent(function (Kelas $record) {

                        $siswa = $record

                            ->siswa()

                            ->with([

                                'kehadiranIbadah' => fn($query) =>

                                $query->whereDate(
                                    'tanggal',
                                    $this->getTanggal()
                                ),

                            ])

                            ->orderBy('nama')

                            ->get();

                        return view(
                            'livewire.detail-kehadiran-ibadah-kelas',
                            [

                                'siswa' => $siswa,

                                'tanggal' => $this->getTanggal(),

                            ]
                        );
                    })

                    ->modalSubmitAction(false)

                    ->modalCancelActionLabel('Tutup'),

            ])

            ->striped();
    }

    public function render(): View
    {
        return view(
            'livewire.rekap-harian-ibadah-table'
        );
    }
}
