<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestScenarioResource\Pages;
use App\Filament\Resources\TestScenarioResource\RelationManagers;
use App\Models\TestScenario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestScenarioResource extends Resource
{
    protected static ?string $model = TestScenario::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Testing';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Scenario Details')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->relationship('project', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                                'critical' => 'Critical',
                            ])
                            ->default('medium')
                            ->required(),
                        Forms\Components\Select::make('frequency')
                            ->options([
                                'manual' => 'Manual',
                                'hourly' => 'Hourly',
                                'daily' => 'Daily',
                                'weekly' => 'Weekly',
                            ])
                            ->default('manual')
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Test Steps')
                    ->schema([
                        Forms\Components\Repeater::make('steps')
                            ->schema([
                                Forms\Components\Grid::make(5)
                                    ->schema([
                                        Forms\Components\Select::make('action')
                                            ->options([
                                                'visit' => 'Visit URL',
                                                'click' => 'Click Element',
                                                'type' => 'Type Text',
                                                'hover' => 'Hover Element',
                                                'select' => 'Select Option',
                                                'check' => 'Check Checkbox',
                                                'uncheck' => 'Uncheck Checkbox',
                                                'wait' => 'Wait (ms)',
                                                'assert_text' => 'Assert Text',
                                                'assert_visible' => 'Assert Element Visible',
                                                'screenshot' => 'Take Screenshot',
                                            ])
                                            ->required()
                                            ->live(),
                                        Forms\Components\Select::make('selector_type')
                                            ->label('Selector Type')
                                            ->options([
                                                'css' => 'CSS Selector',
                                                'xpath' => 'XPath',
                                                'text' => 'Text Content',
                                                'role' => 'ARIA Role',
                                                'testid' => 'Test ID',
                                                'placeholder' => 'Placeholder',
                                                'label' => 'Label',
                                                'ai_describe' => '🤖 AI Describe (Natural Language)',
                                            ])
                                            ->default('css')
                                            ->visible(fn(Forms\Get $get) => in_array($get('action'), ['click', 'type', 'hover', 'select', 'check', 'uncheck', 'assert_text', 'assert_visible']))
                                            ->helperText(fn(Forms\Get $get) => match ($get('selector_type')) {
                                                'xpath' => 'e.g., //button[@id="submit"]',
                                                'text' => 'e.g., Submit or Login',
                                                'role' => 'e.g., button or role=button[name="Submit"]',
                                                'testid' => 'e.g., login-button',
                                                'placeholder' => 'e.g., Enter your email',
                                                'label' => 'e.g., Email Address',
                                                'ai_describe' => 'e.g., "the blue submit button in the footer" or "the login form email input"',
                                                default => 'e.g., #submit-btn or .btn-primary'
                                            }),
                                        Forms\Components\TextInput::make('selector')
                                            ->placeholder('Element selector')
                                            ->visible(fn(Forms\Get $get) => in_array($get('action'), ['click', 'type', 'hover', 'select', 'check', 'uncheck', 'assert_text', 'assert_visible'])),
                                        Forms\Components\TextInput::make('value')
                                            ->placeholder('Input text or expected value')
                                            ->visible(fn(Forms\Get $get) => in_array($get('action'), ['visit', 'type', 'select', 'wait', 'assert_text'])),
                                        Forms\Components\TextInput::make('description')
                                            ->placeholder('Optional note'),
                                    ]),
                            ])
                            ->orderable()
                            ->cloneable()
                            ->itemLabel(fn(array $state): ?string => $state['action'] . ' ' . ($state['selector'] ?? '')),
                    ]),

                Forms\Components\Section::make('Network Mocking (Optional)')
                    ->description('Mock API responses, block resources, or simulate slow networks')
                    ->schema([
                        Forms\Components\Repeater::make('network_mocks')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('type')
                                            ->options([
                                                'mock_api' => 'Mock API Response',
                                                'block_resource' => 'Block Resource',
                                                'throttle' => 'Throttle Network',
                                            ])
                                            ->required()
                                            ->live(),
                                        Forms\Components\TextInput::make('url')
                                            ->label('URL Pattern')
                                            ->placeholder('**/api/users or **/*.{png,jpg}')
                                            ->visible(fn(Forms\Get $get) => in_array($get('type'), ['mock_api', 'block_resource']))
                                            ->helperText('Use wildcards: ** for any path, * for segment'),
                                        Forms\Components\TextInput::make('pattern')
                                            ->label('Resource Pattern')
                                            ->placeholder('**/*.{png,jpg,css}')
                                            ->visible(fn(Forms\Get $get) => $get('type') === 'block_resource'),
                                        Forms\Components\TextInput::make('delay_ms')
                                            ->label('Delay (ms)')
                                            ->numeric()
                                            ->default(1000)
                                            ->visible(fn(Forms\Get $get) => $get('type') === 'throttle'),
                                        Forms\Components\Textarea::make('response')
                                            ->label('Mock Response (JSON)')
                                            ->placeholder('{"success": true, "data": []}')
                                            ->visible(fn(Forms\Get $get) => $get('type') === 'mock_api')
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('status')
                                            ->label('HTTP Status')
                                            ->numeric()
                                            ->default(200)
                                            ->visible(fn(Forms\Get $get) => $get('type') === 'mock_api'),
                                    ]),
                            ])
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => ($state['type'] ?? 'Mock') . ': ' . ($state['url'] ?? $state['pattern'] ?? 'Network')),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'gray' => 'low',
                        'info' => 'medium',
                        'warning' => 'high',
                        'danger' => 'critical',
                    ])
                    ->icons([
                        'heroicon-o-minus' => 'low',
                        'heroicon-o-arrow-up' => 'medium',
                        'heroicon-o-exclamation-triangle' => 'high',
                        'heroicon-o-fire' => 'critical',
                    ])
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('steps')
                    ->getStateUsing(fn(TestScenario $record) => count($record->steps ?? []) . ' steps'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('generate_ai')
                    ->label('🤖 Generate Steps')
                    ->icon('heroicon-o-sparkles')
                    ->color('warning')
                    ->form([
                        Forms\Components\Textarea::make('requirement')
                            ->label('Describe what to test')
                            ->placeholder('Example: Test that users can login with valid credentials and see their dashboard')
                            ->required()
                            ->rows(4)
                            ->helperText('Describe the test scenario in natural language. AI will generate the steps for you.'),
                        Forms\Components\Toggle::make('append_mode')
                            ->label('Append to existing steps')
                            ->helperText('If enabled, new steps will be added after existing ones. Otherwise, existing steps will be replaced.')
                            ->default(false),
                    ])
                    ->action(function (TestScenario $record, array $data) {
                        try {
                            $generator = app(\App\Services\AiTestGeneratorService::class);
                            $steps = $generator->generateFromRequirement(
                                $data['requirement'],
                                $record->project
                            );

                            if (empty($steps)) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Generation Failed')
                                    ->body('AI could not generate valid steps. Please try rephrasing your requirement.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Append or replace based on user choice
                            if ($data['append_mode'] ?? false) {
                                $existingSteps = $record->steps ?? [];
                                $finalSteps = array_merge($existingSteps, $steps);
                            } else {
                                $finalSteps = $steps;
                            }

                            $record->update(['steps' => $finalSteps]);

                            $mode = ($data['append_mode'] ?? false) ? 'appended' : 'generated';
                            \Filament\Notifications\Notification::make()
                                ->title('Test Steps ' . ucfirst($mode) . '!')
                                ->body(count($steps) . ' steps ' . $mode . '. Review and edit as needed.')
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Generation Error')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Generate Test Steps with AI')
                    ->modalDescription('AI will analyze your requirement and generate appropriate test steps.')
                    ->modalSubmitActionLabel('Generate'),

                Tables\Actions\Action::make('run')
                    ->label('Run Test')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('agent_id')
                            ->label('Select Agent')
                            ->relationship('project.agents', 'name') // Only show agents for this project
                            ->required(),
                    ])
                    ->action(function (TestScenario $record, array $data) {
                        $run = \App\Models\Run::create([
                            'project_id' => $record->project_id,
                            'agent_id' => $data['agent_id'],
                            'test_scenario_id' => $record->id,
                            'status' => 'queued',
                        ]);

                        \App\Jobs\ExecuteTestJob::dispatch($run);

                        \Filament\Notifications\Notification::make()
                            ->title('Test Scenario Queued')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestScenarios::route('/'),
            'create' => Pages\CreateTestScenario::route('/create'),
            'edit' => Pages\EditTestScenario::route('/{record}/edit'),
        ];
    }
}
