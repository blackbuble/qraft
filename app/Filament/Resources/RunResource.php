<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RunResource\Pages;
use App\Filament\Resources\RunResource\RelationManagers;
use App\Models\Run;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RunResource extends Resource
{
    protected static ?string $model = Run::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationGroup = 'Testing';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Inspection Overview')
                    ->schema([
                        Forms\Components\Group::make([
                            Forms\Components\Select::make('project_id')
                                ->relationship('project', 'name')
                                ->disabled(),
                            Forms\Components\Select::make('agent_id')
                                ->relationship('agent', 'name')
                                ->disabled(),
                        ])->columns(2),
                        Forms\Components\TextInput::make('status')
                            ->disabled(),
                    ]),

                Forms\Components\Section::make('AI Analysis')
                    ->description('Automated visual analysis provided by the AI Agent.')
                    ->headerActions([
                        \Filament\Forms\Components\Actions\Action::make('download_pdf')
                            ->label('Download PDF')
                            ->icon('heroicon-o-document-arrow-down')
                            ->action(function (Run $record) {
                                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.run-report', ['run' => $record]);
                                return response()->streamDownload(
                                    fn() => print ($pdf->output()),
                                    "run-{$record->id}-report.pdf"
                                );
                            }),
                    ])
                    ->schema([
                        Forms\Components\Placeholder::make('ai_analysis')
                            ->hiddenLabel()
                            ->content(fn(Run $record) => new \Illuminate\Support\HtmlString(
                                \Illuminate\Support\Str::markdown($record->result['ai_analysis'] ?? '*Pending analysis...*')
                            )),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Visual Evidence')
                    ->schema([
                        Forms\Components\Placeholder::make('screenshot')
                            ->hiddenLabel()
                            ->content(fn(Run $record) => $record->result['screenshot'] ?? null
                                ? new \Illuminate\Support\HtmlString('<img src="data:image/jpeg;base64,' . $record->result['screenshot'] . '" style="max-width: 100%; border: 1px solid #ccc; border-radius: 8px;" />')
                                : 'No screenshot available')
                    ]),

                Forms\Components\Section::make('Technical Context')
                    ->description('Captured technical metadata from the browser.')
                    ->schema([
                        Forms\Components\Placeholder::make('network_errors_display')
                            ->label('Network Failures')
                            ->content(fn(Run $record) => !empty($record->result['network_errors'])
                                ? new \Illuminate\Support\HtmlString(
                                    '<div class="overflow-x-auto"><table class="w-full text-sm text-left border-collapse">' .
                                    '<thead><tr class="border-b"><th>Type</th><th>Status</th><th>Method</th><th>URL</th></tr></thead>' .
                                    '<tbody>' .
                                    implode('', array_map(
                                        fn($err) =>
                                        "<tr class='border-b'>
                                            <td class='py-1'>" . ($err['type'] ?? 'error') . "</td>
                                            <td class='py-1 font-bold text-danger-500'>" . ($err['status'] ?? ($err['error'] ?? 'failed')) . "</td>
                                            <td class='py-1'>" . ($err['method'] ?? 'GET') . "</td>
                                            <td class='py-1 truncate max-w-xs' title='{$err['url']}'>{$err['url']}</td>
                                        </tr>",
                                        $record->result['network_errors']
                                    )) .
                                    '</tbody></table></div>'
                                )
                                : 'No network errors captured.'),
                    ])
                    ->collapsible()
                    ->collapsed(fn(Run $record) => empty($record->result['network_errors'])),

                Forms\Components\Section::make('Console Logs')
                    ->schema([
                        Forms\Components\Textarea::make('logs')
                            ->hiddenLabel()
                            ->rows(10)
                            ->disabled(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make('Raw Output')
                    ->schema([
                        Forms\Components\KeyValue::make('result')
                            ->hiddenLabel(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')->searchable(),
                Tables\Columns\TextColumn::make('agent.name'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'pending',
                        'warning' => 'running',
                        'success' => 'success',
                        'danger' => 'failed',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-arrow-path' => 'running',
                        'heroicon-o-check-circle' => 'success',
                        'heroicon-o-x-circle' => 'failed',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('completed_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->poll('5s');
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
            'index' => Pages\ListRuns::route('/'),
            'create' => Pages\CreateRun::route('/create'),
            'edit' => Pages\EditRun::route('/{record}/edit'),
        ];
    }
}
