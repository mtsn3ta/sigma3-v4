<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Pegawai;
use Filament\Notifications\Notification;

class EditProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.edit-profile';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Edit Profil Saya';

    public ?Pegawai $pegawai = null;

    public ?array $passwordData = [];

    public ?string $signatureBase64 = null;

    public function mount(): void
    {
        $this->pegawai = Pegawai::where(
            'user_id',
            Auth::id(),
        )->firstOrFail();

        $this->passwordForm->fill();
    }

    public function passwordForm(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Ganti Password')
                    ->description('Kosongkan bagian ini jika Anda tidak ingin mengubah password.')
                    ->schema([

                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->revealable(),

                        TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->confirmed(),

                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->revealable(),

                    ]),

            ])
            ->statePath('passwordData');
    }

    public function updatePassword(): void
    {
        $data = $this->passwordForm->getState();

        if (blank($data['password'] ?? null)) {

            Notification::make()
                ->warning()
                ->title('Tidak ada perubahan')
                ->body('Isi field password baru jika ingin mengganti password.')
                ->send();

            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! Hash::check($data['current_password'] ?? '', $user->password)) {

            Notification::make()
                ->danger()
                ->title('Password saat ini salah')
                ->send();

            return;
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        $this->passwordForm->fill();

        Notification::make()
            ->success()
            ->title('Password berhasil diubah')
            ->send();
    }

    public function saveSignature(): void
    {
        if (blank($this->signatureBase64)) {

            Notification::make()
                ->warning()
                ->title('Belum ada tanda tangan')
                ->body('Silakan buat tanda tangan pada kanvas terlebih dahulu.')
                ->send();

            return;
        }

        $raw = str_replace('data:image/png;base64,', '', $this->signatureBase64);
        $raw = str_replace(' ', '+', $raw);
        $decoded = base64_decode($raw);

        if ($this->pegawai->ttd_digital) {
            Storage::disk('public')->delete($this->pegawai->ttd_digital);
        }

        $filename = 'pegawai/ttd/ttd_' . $this->pegawai->id . '_' . now()->timestamp . '.png';

        Storage::disk('public')->put($filename, $decoded);

        $this->pegawai->update([
            'ttd_digital' => $filename,
        ]);

        $this->signatureBase64 = null;

        Notification::make()
            ->success()
            ->title('Tanda tangan berhasil disimpan')
            ->body('Tanda tangan ini akan otomatis dipakai saat Anda approve dokumen.')
            ->send();
    }

    public function clearSignatureFile(): void
    {
        if ($this->pegawai->ttd_digital) {

            Storage::disk('public')->delete($this->pegawai->ttd_digital);

            $this->pegawai->update([
                'ttd_digital' => null,
            ]);
        }

        Notification::make()
            ->success()
            ->title('Tanda tangan tersimpan telah dihapus')
            ->send();
    }
}
