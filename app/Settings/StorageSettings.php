<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class StorageSettings extends Settings
{
    public string $artifact_disk = 'local';
    public string $artifact_path = 'qraft/artifacts';
    public string $visibility = 'public';
    public bool $store_screenshots = true;
    public bool $store_videos = false;

    // S3 Credentials
    public ?string $s3_key = null;
    public ?string $s3_secret = null;
    public ?string $s3_region = null;
    public ?string $s3_bucket = null;
    public ?string $s3_url = null;
    public ?string $s3_endpoint = null;
    public bool $s3_use_path_style_endpoint = false;

    public static function group(): string
    {
        return 'storage';
    }
}
