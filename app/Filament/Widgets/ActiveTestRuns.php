<?php

namespace App\Filament\Widgets;

use App\Models\Run;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ActiveTestRuns extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Active Test Runs';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Run::query()->where('status', 'running')->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-m-rectangle-stack'),
                Tables\Columns\TextColumn::make('testScenario.title')
                    ->label('Scenario')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Started At')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn(Run $record): string => route('filament.admin.resources.runs.view', $record))
                    ->icon('heroicon-m-eye'),
            ])
            ->poll('5s'); // Auto-refresh every 5 seconds
    }
}
