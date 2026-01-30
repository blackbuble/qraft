<?php

namespace App\Services;

use App\Settings\StorageSettings;
use Illuminate\Support\Facades\Config;

class StorageConfigService
{
    public function apply(): void
    {
        $settings = app(StorageSettings::class);

        if ($settings->artifact_disk === 's3' && !empty($settings->s3_key)) {
            Config::set('filesystems.disks.s3.key', $settings->s3_key);
            Config::set('filesystems.disks.s3.secret', $settings->s3_secret);
            Config::set('filesystems.disks.s3.region', $settings->s3_region);
            Config::set('filesystems.disks.s3.bucket', $settings->s3_bucket);

            if ($settings->s3_url) {
                Config::set('filesystems.disks.s3.url', $settings->s3_url);
            }

            if ($settings->s3_endpoint) {
                Config::set('filesystems.disks.s3.endpoint', $settings->s3_endpoint);
            }

            Config::set('filesystems.disks.s3.use_path_style_endpoint', $settings->s3_use_path_style_endpoint);
        }
    }
}
