<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Organization;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Support\Str;

class EditOrganizationProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Organization Settings';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Organization Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Organization Name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                // Only auto-update slug if it hasn't been manually changed
                                if (empty($get('slug')) || Str::slug($get('name')) === $get('slug')) {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        TextInput::make('slug')
                            ->label('Organization Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Organization::class, 'slug', ignoreRecord: true)
                            ->alphaDash()
                            ->helperText('This will be used in your organization URL'),
                    ]),

                Section::make('Subscription Information')
                    ->schema([
                        TextInput::make('subscription_plan')
                            ->label('Current Plan')
                            ->disabled()
                            ->formatStateUsing(fn($state) => ucfirst($state ?? 'free')),
                        TextInput::make('subscription_status')
                            ->label('Status')
                            ->disabled()
                            ->formatStateUsing(fn($state) => ucfirst($state ?? 'active')),
                        TextInput::make('trial_ends_at')
                            ->label('Trial Ends')
                            ->disabled()
                            ->formatStateUsing(fn($state) => $state ? $state->format('M d, Y') : 'N/A'),
                    ])
                    ->columns(3),
            ]);
    }
}
