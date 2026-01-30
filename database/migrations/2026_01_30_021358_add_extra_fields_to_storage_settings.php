<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('storage.artifact_path', 'qraft/artifacts');
        $this->migrator->add('storage.visibility', 'public');
    }
};
