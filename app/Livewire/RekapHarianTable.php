<?php

namespace App\Livewire;

use App\Models\Kelas;
use Filament\Actions\Action; // Gunakan Action dari Filament\Actions
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Schemas\Schema;

// Implementasikan semua interface agar sinkron dengan contoh Anda
class RekapHarianTable extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions; // Wajib ada sesuai contoh Anda
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

                    ->live()

                    ->default(now())

                    ->afterStateUpdated(fn() => $this->resetTable()),

            ])

            ->statePath('data');
    }
    protected function getTanggal(): string
    {
        return $this->data['tanggal']
            ?? now()->toDateString();
    }

    protected function countByStatus(string $status): \Closure
    {
        return function (Builder $query) use ($status) {

            $tanggal = $this->getTanggal();

            $query->whereHas(
                'kehadiranSekolah',
                fn(Builder $query) =>

                $query
                    ->whereDate('tanggal', $tanggal)
                    ->where('status', $status)
            );
        };
    }
    public function table(Table $table): Table
    {
        return $table
            ->heading('Rekap Kehadiran Harian')
            ->description(
                fn() =>

                'Tanggal : '

                    . Carbon::parse($this->getTanggal())

                    ->translatedFormat('d F Y')

            )
            ->query(

                Kelas::query()

                    ->withCount([

                        'siswa as hadir_count'      => $this->countByStatus('hadir'),

                        'siswa as terlambat_count'  => $this->countByStatus('terlambat'),

                        'siswa as izin_count'       => $this->countByStatus('izin'),

                        'siswa as sakit_count'      => $this->countByStatus('sakit'),

                        'siswa as alfa_count'       => $this->countByStatus('alfa'),

                        'siswa as total_count',

                    ])

            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Kelas')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('hadir_count')
                    ->label('Hadir')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('terlambat_count')->label('Terlambat')->badge()->color('warning'),
                Tables\Columns\TextColumn::make('izin_count')->label('Izin')->badge()->color('info'),
                Tables\Columns\TextColumn::make('sakit_count')->label('Sakit')->badge()->color('info'),
                Tables\Columns\TextColumn::make('alfa_count')->label('Alfa')->badge()->color('danger'),
                Tables\Columns\TextColumn::make('total_count')->label('Total')->weight('bold'),
            ])
            ->actions([
                // Menggunakan Action dari Filament\Actions seperti contoh Anda
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn(Kelas $record) => 'Detail Kehadiran ' . $record->nama_kelas)
                    ->modalContent(function (Kelas $record) {
                        $tanggal = $this->getTanggal();
                        $siswa = $record

                            ->siswa()

                            ->with([

                                'kehadiranSekolah' => fn($query) =>

                                $query->whereDate(
                                    'tanggal',
                                    $tanggal
                                ),

                            ])

                            ->orderBy('nama')

                            ->get();

                        return view('livewire.detail-kehadiran-kelas', [
                            'siswa' => $siswa,
                            'tanggal' => $tanggal,
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
            ])
            ->striped();
    }

    public function render(): View
    {
        return view('livewire.rekap-harian-table');
    }
}
