<?php

namespace App\Filament\SuperAdmin\Resources\OrganizationResource\Pages;

use App\Filament\SuperAdmin\Resources\OrganizationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewOrganization extends ViewRecord
{
    protected static string $resource = OrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Organization Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('slug'),
                        Infolists\Components\TextEntry::make('owner.name')
                            ->label('Owner'),
                        Infolists\Components\TextEntry::make('owner.email')
                            ->label('Owner Email'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Subscription Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('subscription_plan')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'free' => 'gray',
                                'pro' => 'success',
                                'enterprise' => 'warning',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('subscription_status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'active' => 'success',
                                'canceled' => 'danger',
                                'past_due' => 'warning',
                                'trialing' => 'info',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('trial_ends_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('stripe_id')
                            ->label('Stripe Customer ID'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Statistics')
                    ->schema([
                        Infolists\Components\TextEntry::make('users_count')
                            ->label('Team Members')
                            ->state(fn($record) => $record->users()->count()),
                        Infolists\Components\TextEntry::make('projects_count')
                            ->label('Projects')
                            ->state(fn($record) => $record->projects()->count()),
                        Infolists\Components\TextEntry::make('test_scenarios_count')
                            ->label('Test Scenarios')
                            ->state(fn($record) => $record->testScenarios()->count()),
                        Infolists\Components\TextEntry::make('runs_count')
                            ->label('Test Runs')
                            ->state(fn($record) => $record->runs()->count()),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Timestamps')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }
}
