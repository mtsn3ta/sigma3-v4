<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class Login extends BaseLogin
{
    public function getHeading(): string
    {
        return 'SIGMA-3';
    }
    public function getSubHeading(): ?string
    {
        return 'Sistem Informasi Global MTsN 3 Tulungagung';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Username')
            ->placeholder('Masukkan NIP / NIK / NISN')
            ->required()
            ->autocomplete('username')
            ->autofocus();
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['email'],
            'password' => $data['password'],
        ];
    }
}
