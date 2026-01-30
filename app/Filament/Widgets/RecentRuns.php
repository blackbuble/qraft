<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Run;

class RecentRuns extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Run::latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->sortable(),
                Tables\Columns\TextColumn::make('testScenario.title')
                    ->label('Scenario')
                    ->placeholder('Ad-hoc Run'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'queued',
                        'warning' => 'processing',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->label('Ran'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn(Run $record): string => \App\Filament\Resources\RunResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
