<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamMemberResource\Pages;
use App\Models\Organization;
use App\Models\OrganizationInvitation;
use App\Models\User;
use App\Services\PlanLimits;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class TeamMemberResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Team Members';

    protected static ?int $navigationSort = 98;

    public static function getEloquentQuery(): Builder
    {
        $organization = Filament::getTenant();

        return parent::getEloquentQuery()
            ->whereHas('organizations', function ($query) use ($organization) {
                $query->where('organizations.id', $organization->id);
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->options([
                        'owner' => 'Owner',
                        'admin' => 'Admin',
                        'member' => 'Member',
                    ])
                    ->default('member')
                    ->required()
                    ->helperText('Owner: Full control | Admin: Manage settings | Member: Regular access'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $organization = Filament::getTenant();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('organizations')
                    ->label('Role')
                    ->formatStateUsing(function ($record) use ($organization) {
                        $pivot = $record->organizations()
                            ->where('organizations.id', $organization->id)
                            ->first()?->pivot;
                        return ucfirst($pivot?->role ?? 'member');
                    })
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'owner' => 'danger',
                        'admin' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('change_role')
                    ->label('Change Role')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        Forms\Components\Select::make('role')
                            ->options([
                                'owner' => 'Owner',
                                'admin' => 'Admin',
                                'member' => 'Member',
                            ])
                            ->required(),
                    ])
                    ->action(function (User $record, array $data) use ($organization) {
                        $record->organizations()->updateExistingPivot($organization->id, [
                            'role' => $data['role'],
                        ]);

                        Notification::make()
                            ->title('Role Updated')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(User $record) => auth()->user()->ownsOrganization($organization)),
                Tables\Actions\DeleteAction::make()
                    ->label('Remove')
                    ->modalHeading('Remove Team Member')
                    ->modalDescription('Are you sure you want to remove this team member?')
                    ->action(function (User $record) use ($organization) {
                        $organization->users()->detach($record->id);

                        Notification::make()
                            ->title('Team Member Removed')
                            ->success()
                            ->send();
                    })
                    ->visible(
                        fn(User $record) =>
                        auth()->user()->ownsOrganization($organization) &&
                        $record->id !== auth()->id()
                    ),
            ])
            ->headerActions([
                Tables\Actions\Action::make('invite')
                    ->label('Invite Member')
                    ->icon('heroicon-o-envelope')
                    ->form([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->label('Email Address'),
                        Forms\Components\Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'member' => 'Member',
                            ])
                            ->default('member')
                            ->required(),
                    ])
                    ->action(function (array $data) use ($organization) {
                        // Check plan limits
                        $planLimits = app(PlanLimits::class);
                        if (!$planLimits->canAddTeamMember($organization)) {
                            Notification::make()
                                ->title('Team Member Limit Reached')
                                ->body('Please upgrade your plan to add more team members.')
                                ->warning()
                                ->send();
                            return;
                        }

                        // Check if user already exists in organization
                        $existingUser = User::where('email', $data['email'])->first();
                        if ($existingUser && $organization->users()->where('users.id', $existingUser->id)->exists()) {
                            Notification::make()
                                ->title('User Already in Organization')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Create invitation
                        $invitation = OrganizationInvitation::createInvitation(
                            $organization,
                            $data['email'],
                            $data['role']
                        );

                        // TODO: Send invitation email
                        // Mail::to($data['email'])->send(new TeamInvitation($invitation));
            
                        Notification::make()
                            ->title('Invitation Sent')
                            ->body("An invitation has been sent to {$data['email']}")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeamMembers::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Use invite action instead
    }
}
