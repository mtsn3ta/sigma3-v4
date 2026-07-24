<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Facades\Filament;

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
            ->placeholder('Username')
            ->prefixIcon('heroicon-s-user') // Menambahkan ikon di sebelah kiri
            ->required()
            ->autocomplete('username')
            ->autofocus()
            ->extraInputAttributes(['class' => 'text-slate-900']);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->placeholder('*************')
            ->prefixIcon('heroicon-s-lock-closed') // Ikon gembok
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->extraInputAttributes(['name' => 'password']);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['email'],
            'password' => $data['password'],
        ];
    }
    protected function getRedirectUrl(): string
    {
        /** @var \App\Models\User|null $user */
        $user = Filament::auth()->user();

        if ($user?->hasRole('siswa')) {
            return Filament::getPanel('siswa')->getUrl();
        }

        return Filament::getPanel('admin')->getUrl();
    }
}
