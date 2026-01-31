<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Organization;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Str;

class RegisterOrganization extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Create Organization';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Organization Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->label('Organization Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Organization::class, 'slug')
                    ->alphaDash()
                    ->helperText('This will be used in your organization URL'),
            ]);
    }

    protected function handleRegistration(array $data): Organization
    {
        $organization = Organization::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'owner_id' => auth()->id(),
            'subscription_plan' => 'free',
            'subscription_status' => 'active',
            'trial_ends_at' => now()->addDays(14), // 14-day trial
        ]);

        // Add the creator as owner
        $organization->users()->attach(auth()->id(), ['role' => 'owner']);

        return $organization;
    }
}
