<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationGroup = 'Testing';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Textarea::make('description'),
                Forms\Components\TextInput::make('repo_url')->label('Repository / Target URL')->required()->url(),
                Forms\Components\Select::make('status')
                    ->options(['active' => 'Active', 'archived' => 'Archived'])
                    ->default('active')
                    ->required(),
                Forms\Components\TagsInput::make('notification_emails')
                    ->label('Notification Emails')
                    ->placeholder('Add email address')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('repo_url')->limit(30),
                Tables\Columns\BadgeColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('inspect')
                    ->label('Run Inspection')
                    ->icon('heroicon-o-bug-ant')
                    ->form([
                        Forms\Components\Select::make('agent_id')
                            ->label('Select Agent')
                            ->options(\App\Models\Agent::pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (Project $record, array $data) {
                        $run = \App\Models\Run::create([
                            'project_id' => $record->id,
                            'agent_id' => $data['agent_id'],
                            'status' => 'queued',
                        ]);
                        \App\Jobs\ExecuteTestJob::dispatch($run);

                        \Filament\Notifications\Notification::make()
                            ->title('Inspection queued')
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
