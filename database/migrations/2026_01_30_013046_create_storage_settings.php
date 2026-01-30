<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('storage.artifact_disk', 'public');
        $this->migrator->add('storage.store_screenshots', true);
        $this->migrator->add('storage.store_videos', false);
    }
};
