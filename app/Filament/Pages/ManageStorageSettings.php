<?php

namespace App\Filament\Pages;

use App\Settings\StorageSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageStorageSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-server-stack';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Storage';
    protected static ?string $title = 'Storage Settings';
    protected static ?int $sort = 2;

    protected static string $settings = StorageSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Filesystem Configuration')
                    ->description('Select the disk and base folder for storing test result artifacts.')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('artifact_disk')
                                    ->label('Filesystem Disk')
                                    ->options(array_combine(array_keys(config('filesystems.disks')), array_keys(config('filesystems.disks'))))
                                    ->required()
                                    ->suffixIcon('heroicon-m-server'),

                                Forms\Components\TextInput::make('artifact_path')
                                    ->label('Base Directory')
                                    ->required()
                                    ->placeholder('qraft/artifacts')
                                    ->helperText('Relative path within the selected disk.'),

                                Forms\Components\Select::make('visibility')
                                    ->label('File Visibility')
                                    ->options([
                                        'public' => 'Public (Direct URLs)',
                                        'private' => 'Private (Signed URLs required)',
                                    ])
                                    ->required()
                                    ->default('public'),
                            ]),
                    ]),

                Forms\Components\Section::make('AWS S3 Credentials')
                    ->description('Configuration for Amazon S3 or compatible storage services (R2, DigitalOcean Spaces, etc.).')
                    ->visible(fn($get) => $get('artifact_disk') === 's3')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('s3_key')
                                    ->label('Access Key ID')
                                    ->password()
                                    ->autocomplete('new-password')
                                    ->required(),

                                Forms\Components\TextInput::make('s3_secret')
                                    ->label('Secret Access Key')
                                    ->password()
                                    ->autocomplete('new-password')
                                    ->required(),

                                Forms\Components\TextInput::make('s3_region')
                                    ->label('Region')
                                    ->placeholder('us-east-1')
                                    ->required(),

                                Forms\Components\TextInput::make('s3_bucket')
                                    ->label('Bucket Name')
                                    ->required(),

                                Forms\Components\TextInput::make('s3_url')
                                    ->label('Custom URL (Optional)')
                                    ->url(),

                                Forms\Components\TextInput::make('s3_endpoint')
                                    ->label('Custom Endpoint (Optional)')
                                    ->url()
                                    ->helperText('Useful for S3-compatible providers like MinIO or R2.'),

                                Forms\Components\Toggle::make('s3_use_path_style_endpoint')
                                    ->label('Use Path Style Endpoint')
                                    ->default(false),
                            ]),
                    ]),

                Forms\Components\Section::make('Collection Logic')
                    ->description('Choose what data to capture during test runs.')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('store_screenshots')
                                    ->label('Capture Screenshots')
                                    ->helperText('Saves a final full-page screenshot of every test.')
                                    ->default(true),

                                Forms\Components\Toggle::make('store_videos')
                                    ->label('Capture Videos')
                                    ->helperText('Record screen during the whole test execution (Experimental).')
                                    ->default(false),
                            ]),
                    ]),
            ]);
    }
}
