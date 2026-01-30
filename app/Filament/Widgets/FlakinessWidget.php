<?php

namespace App\Filament\Widgets;

use App\Models\TestFlakiness;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class FlakinessWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = '🔥 Flaky Tests Intelligence';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TestFlakiness::query()
                    ->where('flakiness_score', '>', 20)
                    ->with('testScenario.project')
                    ->orderByDesc('flakiness_score')
            )
            ->columns([
                Tables\Columns\TextColumn::make('testScenario.project.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('testScenario.title')
                    ->label('Test Scenario')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(fn($record) => $record->testScenario->title),

                Tables\Columns\BadgeColumn::make('flakiness_score')
                    ->label('Flakiness')
                    ->colors([
                        'success' => static fn($state): bool => $state < 30,
                        'warning' => static fn($state): bool => $state >= 30 && $state < 60,
                        'danger' => static fn($state): bool => $state >= 60,
                    ])
                    ->formatStateUsing(fn($state) => $state . '%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pattern.sequence')
                    ->label('Last 10 Runs')
                    ->formatStateUsing(fn($record) => $record->pattern['sequence'] ?? 'N/A')
                    ->fontFamily('mono')
                    ->tooltip('✓ = Pass, ✗ = Fail'),

                Tables\Columns\TextColumn::make('pass_fail_ratio')
                    ->label('Pass/Fail')
                    ->getStateUsing(fn($record) => "{$record->pass_count}/{$record->fail_count}")
                    ->sortable(query: function ($query, string $direction) {
                        return $query->orderBy('pass_count', $direction);
                    }),

                Tables\Columns\TextColumn::make('pattern.time_based.description')
                    ->label('Time Pattern')
                    ->default('None detected')
                    ->limit(30)
                    ->tooltip(fn($record) => $record->pattern['time_based']['description'] ?? null),

                Tables\Columns\TextColumn::make('ai_diagnosis')
                    ->label('AI Diagnosis')
                    ->limit(50)
                    ->tooltip(fn($record) => $record->ai_diagnosis)
                    ->wrap(),

                Tables\Columns\TextColumn::make('suggested_fix')
                    ->label('Suggested Fix')
                    ->limit(50)
                    ->tooltip(fn($record) => $record->suggested_fix)
                    ->wrap(),

                Tables\Columns\TextColumn::make('last_analyzed_at')
                    ->label('Last Analyzed')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_test')
                    ->label('View Test')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => route('filament.admin.resources.test-scenarios.edit', $record->testScenario)),

                Tables\Actions\Action::make('reanalyze')
                    ->label('Re-analyze')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function ($record) {
                        \App\Jobs\AnalyzeFlakinessJob::dispatch($record->testScenario);

                        \Filament\Notifications\Notification::make()
                            ->title('Analysis Queued')
                            ->body('Flakiness analysis has been queued.')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
            ])
            ->defaultSort('flakiness_score', 'desc')
            ->poll('30s'); // Auto-refresh every 30 seconds
    }
}
