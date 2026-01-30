<?php

namespace App\Filament\Pages;

use App\Settings\AiSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageAiSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'AI Settings';
    protected static ?int $navigationSort = 1;

    protected static string $settings = AiSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Configuration')
                    ->description('Set the default AI behavior for the system.')
                    ->schema([
                        Forms\Components\TextInput::make('default_provider')
                            ->label('Default Provider ID')
                            ->placeholder('e.g., openai')
                            ->required()
                            ->helperText('The ID of the provider (defined below) to use by default.'),
                    ]),

                Forms\Components\Section::make('AI Providers')
                    ->description('Manage connections to third-party AI services.')
                    ->schema([
                        Forms\Components\Repeater::make('providers')
                            ->hiddenLabel()
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('id')
                                            ->label('Provider ID')
                                            ->placeholder('openai')
                                            ->required()
                                            ->distinct(),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Display Name')
                                            ->placeholder('OpenAI (GPT-4)')
                                            ->required(),
                                        Forms\Components\TextInput::make('model')
                                            ->label('Model Name')
                                            ->placeholder('gpt-4o')
                                            ->required(),
                                        Forms\Components\TextInput::make('url')
                                            ->label('Base URL')
                                            ->placeholder('https://api.openai.com/v1')
                                            ->required()
                                            ->url(),
                                    ]),
                                Forms\Components\TextInput::make('key')
                                    ->label('API Key')
                                    ->password()
                                    ->revealable()
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                            ->collapsed(),
                    ]),
            ]);
    }
}
