<?php

namespace App\Filament\Resources\TestScenarioResource\Pages;

use App\Filament\Resources\TestScenarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestScenarios extends ListRecords
{
    protected static string $resource = TestScenarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('generate_ai')
                ->label('Generate with AI')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->form([
                    \Filament\Forms\Components\Select::make('project_id')
                        ->relationship('project', 'name') // Assuming 'project' relationship exists on TestScenario model, which it does.
                        ->label('Project')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('title')
                        ->label('Scenario Title')
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('prompt')
                        ->label('Describe the Test')
                        ->helperText('Example: "Go to google.com, search for Laravel, and check if the first result contains Laravel."')
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data) {
                    $aiService = app(\App\Services\AiService::class);

                    try {
                        $steps = $aiService->generateTestSteps($data['prompt']);

                        $scenario = \App\Models\TestScenario::create([
                            'project_id' => $data['project_id'],
                            'title' => $data['title'],
                            'description' => $data['prompt'],
                            'steps' => $steps,
                            'priority' => 'medium',
                            'is_active' => true,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Scenario Generated')
                            ->success()
                            ->send();

                        return redirect()->to(TestScenarioResource::getUrl('edit', ['record' => $scenario]));

                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->title('Generation Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
