<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\OrganizationResource\Pages;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Platform';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Organization Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('owner_id')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Subscription')
                    ->schema([
                        Forms\Components\Select::make('subscription_plan')
                            ->options([
                                'free' => 'Free',
                                'pro' => 'Pro',
                                'enterprise' => 'Enterprise',
                            ])
                            ->required(),
                        Forms\Components\Select::make('subscription_status')
                            ->options([
                                'active' => 'Active',
                                'canceled' => 'Canceled',
                                'past_due' => 'Past Due',
                                'trialing' => 'Trialing',
                            ])
                            ->required(),
                        Forms\Components\DateTimePicker::make('trial_ends_at')
                            ->label('Trial Ends At'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\KeyValue::make('settings')
                            ->label('Custom Settings'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subscription_plan')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'free' => 'gray',
                        'pro' => 'success',
                        'enterprise' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('subscription_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'canceled' => 'danger',
                        'past_due' => 'warning',
                        'trialing' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Members')
                    ->sortable(),
                Tables\Columns\TextColumn::make('projects_count')
                    ->counts('projects')
                    ->label('Projects')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subscription_plan')
                    ->options([
                        'free' => 'Free',
                        'pro' => 'Pro',
                        'enterprise' => 'Enterprise',
                    ]),
                Tables\Filters\SelectFilter::make('subscription_status')
                    ->options([
                        'active' => 'Active',
                        'canceled' => 'Canceled',
                        'past_due' => 'Past Due',
                        'trialing' => 'Trialing',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
            'view' => Pages\ViewOrganization::route('/{record}'),
        ];
    }
}
