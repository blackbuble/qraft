<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('ai.default_provider', 'openai');
        $this->migrator->add('ai.providers', [
            [
                'id' => 'openai',
                'name' => 'OpenAI (GPT-4o)',
                'model' => 'gpt-4o',
                'key' => '',
                'url' => 'https://api.openai.com/v1',
            ],
            [
                'id' => 'gemini',
                'name' => 'Google Gemini',
                'model' => 'gemini-1.5-pro',
                'key' => '',
                'url' => 'https://generativelanguage.googleapis.com/v1beta',
            ],
        ]);
    }
};
