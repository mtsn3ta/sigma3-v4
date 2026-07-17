<?php

namespace App\Filament\Student\Pages;

use App\Models\Siswa;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use App\Services\StudentProfile\UpdateProfileService;
use App\Services\StudentProfile\UpdatePhotoService;
use App\Services\StudentProfile\UpdatePasswordService;

class Profile extends Page
{
    protected static ?string $navigationLabel = 'Profil';

    protected static ?string $title = 'Profil Saya';

    protected static ?int $navigationSort = 1;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected string $view = 'filament.student.pages.profile';

    public Siswa $siswa;

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $this->siswa = $user->siswa()
            ->with([
                'kelasAktif.kelas',
                'kelasAktif.tahunAjaran',
            ])
            ->firstOrFail();
    }
    protected function getHeaderActions(): array
    {
        return [

            Action::make('editProfile')

                ->label('Edit Profil')

                ->icon('heroicon-o-pencil-square')

                ->color('warning')

                ->modalWidth('5xl')

                ->fillForm(function () {
                    return $this->siswa->toArray(); // Shortcut paling cepat dan aman!
                })

                ->schema([
                    Tabs::make('tabs')
                        ->tabs([
                            Tabs\Tab::make('Biodata')
                                ->schema([
                                    TextInput::make('nisn')
                                        ->label('NISN')
                                        ->disabled(), // Tidak bisa diedit
                                    TextInput::make('nama')
                                        ->label('Nama Lengkap')
                                        ->disabled(), // Tidak bisa diedit
                                    TextInput::make('jenis_kelamin'),
                                    TextInput::make('tempat_lahir'),
                                    DatePicker::make('tanggal_lahir'),
                                    Textarea::make('alamat')->rows(3)->columnSpanFull(),
                                    TextInput::make('no_hp'),
                                ])->columns(2),

                            Tabs\Tab::make('Data Keluarga') // Menggabungkan ortu & wali agar lebih efisien
                                ->schema([
                                    TextInput::make('nama_ayah'),
                                    TextInput::make('nama_ibu'),
                                    TextInput::make('nik_ayah'),
                                    TextInput::make('nik_ibu'),
                                    TextInput::make('pekerjaan_ayah'),
                                    TextInput::make('pekerjaan_ibu'),
                                    Textarea::make('alamat_ortu')->rows(2)->columnSpanFull(),
                                    TextInput::make('telpon_ortu'),

                                    TextInput::make('nama_wali'),
                                    TextInput::make('pekerjaan_wali'),
                                    TextInput::make('telpon_wali'),
                                    Textarea::make('alamat_wali')->columnSpanFull(),
                                ])->columns(2),

                            Tabs\Tab::make('Lainnya')
                                ->schema([
                                    TextInput::make('anak_ke')->numeric(),
                                    TextInput::make('sekolah_asal'),
                                ]),

                            Tabs\Tab::make('Foto')
                                ->schema([
                                    FileUpload::make('foto')
                                        ->image()
                                        ->directory('siswa')
                                        ->disk('public'),
                                ]),

                            Tabs\Tab::make('Password')
                                ->schema([
                                    TextInput::make('current_password')
                                        ->password()
                                        ->label('Password Lama')
                                        ->required()
                                        ->currentPassword() // <--- VALIDASI OTOMATIS LARAVEL
                                        ->dehydrated(false),

                                    TextInput::make('password')
                                        ->password()
                                        ->label('Password Baru')
                                        ->required()
                                        ->minLength(8) // Tambahkan batasan minimal
                                        ->dehydrated(false),

                                    TextInput::make('password_confirmation')
                                        ->password()
                                        ->label('Konfirmasi Password')
                                        ->required()
                                        ->same('password') // Pastikan sama dengan password baru
                                        ->dehydrated(false),
                                ]),
                        ]),
                ])

                ->action(function (array $data) {

                    /*
                |--------------------------------------------------------------------------
                | Biodata
                |--------------------------------------------------------------------------
                */

                    app(UpdateProfileService::class)

                        ->execute(
                            $this->siswa,
                            $data,
                        );

                    /*
                |--------------------------------------------------------------------------
                | Foto
                |--------------------------------------------------------------------------
                */
                    if (isset($data['foto'])) {
                        $this->siswa->foto = $data['foto'];
                        $this->siswa->save();
                    }
                    app(UpdatePhotoService::class)

                        ->execute(
                            $this->siswa,
                            $data['foto'] ?? null,
                        );

                    /*
                |--------------------------------------------------------------------------
                | Password
                |--------------------------------------------------------------------------
                */

                    if (
                        filled($data['password'] ?? null)
                    ) {

                        app(UpdatePasswordService::class)

                            ->execute(

                                Auth::user(),

                                $data['current_password'],

                                $data['password'],

                            );
                    }

                    $this->mount();

                    Notification::make()

                        ->title('Profil berhasil diperbarui.')

                        ->success()

                        ->send();
                }),

        ];
    }
}
