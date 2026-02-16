<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BaseLogin;

class SuperAdminLogin extends BaseLogin
{
    public function getHeading(): string
    {
        return 'Super Admin Access';
    }

    public function getSubHeading(): string|null
    {
        return 'Platform Administration';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email Address')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->prefixIcon('heroicon-m-shield-check')
            ->placeholder('admin@qraft.test');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->revealable()
            ->required()
            ->prefixIcon('heroicon-m-lock-closed')
            ->placeholder('••••••••');
    }

    public function getTitle(): string
    {
        return 'Super Admin Login';
    }
}
