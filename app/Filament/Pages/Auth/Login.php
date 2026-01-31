<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getHeading(): string|Htmlable
    {
        return 'Welcome to QRAFT';
    }

    public function getSubHeading(): string|Htmlable|null
    {
        return 'AI-Powered Quality Intelligence Platform';
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent()
                            ->label('Email Address')
                            ->placeholder('Enter your email'),
                        $this->getPasswordFormComponent()
                            ->label('Password')
                            ->placeholder('Enter your password'),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/login.form.email.label'))
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1])
            ->prefixIcon('heroicon-m-envelope')
            ->placeholder('you@example.com');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/login.form.password.label'))
            ->password()
            ->revealable()
            ->required()
            ->extraInputAttributes(['tabindex' => 2])
            ->prefixIcon('heroicon-m-lock-closed')
            ->placeholder('••••••••');
    }

    public function getTitle(): string|Htmlable
    {
        return 'Sign in to QRAFT';
    }
}
