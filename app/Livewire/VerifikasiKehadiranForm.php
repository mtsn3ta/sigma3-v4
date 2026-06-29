<?php

namespace App\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View as ViewContract;
use Filament\Schemas\Components\View as SchemaView;
use Livewire\Component;
use App\Support\KelasHelper;
use App\Support\SiswaHelper;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\Siswa;
use App\Models\Pegawai;
use App\Services\Kehadiran\KehadiranSekolahService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class VerifikasiKehadiranForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'tanggal' => today()->toDateString(),
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

                        Section::make('Verifikasi Kehadiran')
                            ->columnSpan(2)
                            ->schema([

                                DatePicker::make('tanggal')
                                    ->required(),

                                Select::make('kelas_id')
                                    ->label('Kelas')
                                    ->options(KelasHelper::options())
                                    ->live()
                                    ->searchable()
                                    ->required(),

                                Select::make('siswa_id')
                                    ->label('Siswa')
                                    ->options(fn(callable $get) => SiswaHelper::belumRfidOptions(
                                        $get('kelas_id')
                                    ))
                                    ->live()
                                    ->searchable()
                                    ->required(),

                                Radio::make('status')
                                    ->label('Status')
                                    ->options([
                                        'hadir' => 'Hadir',
                                        'terlambat' => 'Terlambat',
                                        'izin' => 'Izin',
                                        'sakit' => 'Sakit',
                                        'alfa' => 'Alfa',
                                    ])
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {

                                        if ($state === 'hadir') {
                                            $set('jam_datang', '06:45');
                                        }

                                        if ($state === 'terlambat') {
                                            $set('jam_datang', '07:01');
                                        }

                                        if (in_array($state, ['izin', 'sakit', 'alfa'])) {
                                            $set('jam_datang', null);
                                        }
                                    })
                                    ->required(),

                                TimePicker::make('jam_datang')
                                    ->label('Jam Datang')
                                    ->seconds(false)
                                    ->visible(fn(callable $get) => in_array(
                                        $get('status'),
                                        ['hadir', 'terlambat']
                                    ))
                                    ->required(fn(callable $get) => in_array(
                                        $get('status'),
                                        ['hadir', 'terlambat']
                                    )),

                                Textarea::make('keterangan')
                                    ->label('Keterangan / Catatan')
                                    ->placeholder(fn(callable $get) => match ($get('status')) {
                                        'izin' => 'Contoh: Izin mengikuti acara keluarga.',
                                        'sakit' => 'Contoh: Demam, izin dari orang tua.',
                                        'alfa' => 'Contoh: Tidak hadir tanpa pemberitahuan.',
                                        default => '',
                                    })
                                    ->rows(3)
                                    ->visible(fn(callable $get) => in_array(
                                        $get('status'),
                                        ['izin', 'sakit', 'alfa']
                                    ))
                                    ->required(fn(callable $get) => in_array(
                                        $get('status'),
                                        ['izin', 'sakit', 'alfa']
                                    )),

                            ]),

                        Section::make('Panduan')
                            ->columnSpan(1)
                            ->schema([
                                SchemaView::make('filament.guru-piket.panduan-kehadiran'),
                            ]),

                    ]),

            ])
            ->statePath('data');
    }

    public function submit(
        KehadiranSekolahService $service,
    ): void {
        $data = $this->form->getState();

        $siswa = Siswa::findOrFail(
            $data['siswa_id']
        );

        $user = Auth::user();

        $petugas = Pegawai::where(
            'user_id',
            $user->id,
        )->firstOrFail();

        switch ($data['status']) {

            case 'hadir':

                $service->markManualHadir(

                    siswa: $siswa,

                    jamDatang: $data['jam_datang'],

                    petugas: $petugas,

                );

                break;

            case 'terlambat':

                $service->markManualTerlambat(

                    siswa: $siswa,

                    jamDatang: $data['jam_datang'],

                    petugas: $petugas,

                );

                break;

            case 'izin':

                $service->markIzin(

                    siswa: $siswa,

                    keterangan: $data['keterangan'],

                    petugas: $petugas,

                );

                break;

            case 'sakit':

                $service->markSakit(

                    siswa: $siswa,

                    keterangan: $data['keterangan'],

                    petugas: $petugas,

                );

                break;

            case 'alfa':

                $service->markAlfa(

                    siswa: $siswa,

                    keterangan: $data['keterangan'],

                    petugas: $petugas,

                );

                break;
        }

        Notification::make()
            ->success()
            ->title('Berhasil')
            ->body("Verifikasi kehadiran {$siswa->nama} berhasil disimpan.")
            ->send();

        $kelas = $data['kelas_id'];

        $tanggal = $data['tanggal'];

        $this->form->fill([

            'tanggal' => $tanggal,

            'kelas_id' => $kelas,

        ]);
    }

    public function render(): ViewContract
    {
        return view('livewire.verifikasi-kehadiran-form');
    }
}
