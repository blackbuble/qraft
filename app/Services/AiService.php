<?php

namespace App\Services;

use App\Settings\AiSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    public function analyze(string $prompt, ?string $providerId = null): string
    {
        $settings = app(AiSettings::class);
        $providerId = $providerId ?? $settings->default_provider;

        $provider = collect($settings->providers)->firstWhere('id', $providerId);

        if (!$provider) {
            throw new \Exception("Provider {$providerId} not found in settings.");
        }

        if ($provider['id'] === 'gemini') {
            return $this->analyzeWithGemini($prompt, $provider);
        }

        return $this->analyzeWithOpenAi($prompt, $provider);
    }

    public function analyzeImage(string $prompt, string $base64Image, ?string $providerId = null): string
    {
        $settings = app(AiSettings::class);
        $providerId = $providerId ?? $settings->default_provider;
        $provider = collect($settings->providers)->firstWhere('id', $providerId);

        if (!$provider) {
            throw new \Exception("Provider {$providerId} not found in settings.");
        }

        if ($provider['id'] === 'gemini') {
            return $this->analyzeImageWithGemini($prompt, $base64Image, $provider);
        }

        return $this->analyzeImageWithOpenAi($prompt, $base64Image, $provider);
    }

    protected function analyzeWithOpenAi(string $prompt, array $provider): string
    {
        return $this->analyzeImageWithOpenAi($prompt, null, $provider);
    }

    protected function analyzeImageWithOpenAi(string $prompt, ?string $base64Image, array $provider): string
    {
        $messages = [
            ['role' => 'system', 'content' => 'You are QRAFT, an AI Quality Assurance Architect. Analyze screenshots for bugs, UI issues, and layout regressions.'],
        ];

        $userContent = [['type' => 'text', 'text' => $prompt]];

        if ($base64Image) {
            $userContent[] = [
                'type' => 'image_url',
                'image_url' => [
                    'url' => "data:image/jpeg;base64,{$base64Image}"
                ]
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $userContent];

        $response = Http::withToken($provider['key'])
            ->timeout(60)
            ->post($provider['url'] . '/chat/completions', [
                'model' => $provider['model'],
                'messages' => $messages,
                'max_tokens' => 1000,
            ]);

        if ($response->successful()) {
            return $response->json('choices.0.message.content');
        }

        throw new \Exception("OpenAI Error: " . $response->body());
    }

    protected function analyzeWithGemini(string $prompt, array $provider): string
    {
        return $this->analyzeImageWithGemini($prompt, null, $provider);
    }

    protected function analyzeImageWithGemini(string $prompt, ?string $base64Image, array $provider): string
    {
        $url = "{$provider['url']}/models/{$provider['model']}:generateContent?key={$provider['key']}";

        $parts = [['text' => $prompt]];

        if ($base64Image) {
            $parts[] = [
                'inlineData' => [
                    'mimeType' => 'image/jpeg',
                    'data' => $base64Image
                ]
            ];
        }

        $response = Http::timeout(60)
            ->post($url, [
                'contents' => [
                    ['parts' => $parts]
                ]
            ]);

        if ($response->successful()) {
            return $response->json('candidates.0.content.parts.0.text');
        }

        throw new \Exception("Gemini Error: " . $response->body());
    }

    public function generateTestSteps(string $description, ?string $providerId = null): array
    {
        $prompt = <<<EOT
You are a QA automation expert. Convert the following natural language test description into a JSON array of test steps for a browser automation tool.

Supported actions:
- { "action": "visit", "url": "https://example.com" } (Always start with this if URL is implied)
- { "action": "click", "selector": "button#submit" }
- { "action": "type", "selector": "input[name='email']", "value": "user@example.com" }
- { "action": "wait", "timeout": 1000 } (in milliseconds)
- { "action": "assert_text", "selector": ".alert", "value": "Success" }
- { "action": "assert_visible", "selector": "#modal" }

Rules:
1. Return ONLY the JSON array. No markdown formatting, no explanations.
2. Infer Selectors (CSS) intelligently based on the description (e.g., "login button" -> "button[type='submit']" or "#login").
3. If the user mentions a specific URL, use it. If not, use a placeholder or relative path.

Description:
"$description"
EOT;

        $response = $this->analyze($prompt, $providerId);

        // specific cleanup for potential markdown code blocks
        $cleaned = str_replace(['```json', '```'], '', $response);

        $json = json_decode(trim($cleaned), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("AiService::generateTestSteps JSON Error: " . json_last_error_msg() . " | Raw: $response");
            // Fallback: Return a comment step or empty array
            return [];
        }

        return $json;
    }
}
