<?php

namespace App\Livewire;

use App\Models\Pegawai;
use App\Models\Siswa;
use App\Support\KelasHelper;
use App\Support\SiswaHelper;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Services\Kehadiran\KehadiranIbadahService;

class VerifikasiKehadiranIbadahForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'tanggal' => today()->toDateString(),
            'jenis'   => 'dhuha',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make([
                    'default' => 1,
                    'xl' => 3,
                ])
                    ->schema([

                        Section::make('Verifikasi Kehadiran Ibadah')
                            ->columnSpan(2)
                            ->schema([

                                DatePicker::make('tanggal')
                                    ->required(),

                                Select::make('jenis')
                                    ->label('Jenis Ibadah')
                                    ->options([
                                        'dhuha'  => 'Dhuha',
                                        'dzuhur' => 'Dzuhur',
                                    ])
                                    ->live()
                                    ->required(),

                                Select::make('kelas_id')
                                    ->label('Kelas')
                                    ->options(KelasHelper::options())
                                    ->live()
                                    ->searchable()
                                    ->preload()
                                    ->afterStateUpdated(fn(callable $set) => $set('siswa_id', null))
                                    ->required(),

                                Select::make('siswa_id')
                                    ->label('Siswa')
                                    ->options(
                                        fn(callable $get) =>
                                        SiswaHelper::belumVerifikasiIbadahOptions(
                                            kelasId: $get('kelas_id'),
                                            tanggal: $get('tanggal'),
                                            jenis: $get('jenis'),
                                        )
                                    )
                                    ->searchable()
                                    ->live()
                                    ->preload()
                                    ->required(),

                                Radio::make('status')
                                    ->label('Status')
                                    ->options([

                                        'hadir' => 'Hadir',

                                        'menstruasi' => 'Menstruasi',

                                        'tidak_hadir' => 'Tidak Hadir',

                                    ])
                                    ->inline()
                                    ->required(),

                            ]),

                        Section::make('Panduan')
                            ->columnSpan(1)
                            ->schema([

                                SchemaView::make(
                                    'filament.ibadah.panduan-kehadiran-ibadah'
                                ),

                            ]),

                    ]),

            ])
            ->statePath('data');
    }

    public function submit(
        KehadiranIbadahService $service,
    ): void {
        $data = $this->form->getState();

        $siswa = Siswa::findOrFail(
            $data['siswa_id']
        );

        $user = Auth::user();

        $petugas = Pegawai::where(
            'user_id',
            $user->id
        )->firstOrFail();

        switch ($data['status']) {

            case 'hadir':

                $service->markManualHadir(

                    siswa: $siswa,

                    jenis: $data['jenis'],

                    petugas: $petugas,

                    tanggal: $data['tanggal'],

                );

                break;

            case 'menstruasi':

                $service->markMenstruasi(

                    siswa: $siswa,

                    jenis: $data['jenis'],

                    petugas: $petugas,

                    tanggal: $data['tanggal'],

                );

                break;

            case 'tidak_hadir':

                $service->markTidakHadir(

                    siswa: $siswa,

                    jenis: $data['jenis'],

                    petugas: $petugas,

                    tanggal: $data['tanggal'],

                );

                break;
        }

        Notification::make()

            ->success()

            ->title('Berhasil')

            ->body(
                "Verifikasi ibadah {$siswa->nama} berhasil disimpan."
            )

            ->send();

        $kelas = $data['kelas_id'];

        $tanggal = $data['tanggal'];

        $jenis = $data['jenis'];

        $this->form->fill([

            'tanggal' => $tanggal,

            'jenis'   => $jenis,

            'kelas_id' => $kelas,

            'siswa_id' => null,

            'status'  => null,

        ]);
    }

    public function render(): ViewContract
    {
        return view(
            'livewire.verifikasi-kehadiran-ibadah-form'
        );
    }
}
