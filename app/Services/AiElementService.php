<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AiElementService
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Find element by natural language description
     * 
     * @param string $screenshotBase64 Base64 encoded screenshot
     * @param string $description Natural language description (e.g., "the blue submit button in the footer")
     * @return array ['selector' => 'css selector', 'coordinates' => ['x' => 100, 'y' => 200], 'confidence' => 0.95]
     */
    public function findElementByDescription(string $screenshotBase64, string $description): array
    {
        $prompt = "Analyze this screenshot and find the element described as: \"{$description}\".

Your task:
1. Locate the element visually
2. Suggest the BEST CSS selector for it (prefer ID > class > tag+attribute)
3. Provide approximate coordinates (x, y) from top-left
4. Rate your confidence (0.0 to 1.0)

Return ONLY valid JSON:
{
    \"found\": true,
    \"selector\": \"#submit-btn\",
    \"coordinates\": {\"x\": 640, \"y\": 450},
    \"confidence\": 0.95,
    \"reasoning\": \"Found blue button with 'Submit' text in footer section\"
}

If element not found, return: {\"found\": false, \"reasoning\": \"explanation\"}";

        try {
            $response = $this->aiService->analyzeImage($prompt, $screenshotBase64);

            // Parse JSON from response
            $jsonStart = strpos($response, '{');
            $jsonEnd = strrpos($response, '}');

            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonStr = substr($response, $jsonStart, $jsonEnd - $jsonStart + 1);
                $result = json_decode($jsonStr, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $result;
                }
            }

            return [
                'found' => false,
                'reasoning' => 'Failed to parse AI response'
            ];

        } catch (\Exception $e) {
            Log::error("AI Element Discovery failed: " . $e->getMessage());
            return [
                'found' => false,
                'reasoning' => 'AI service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Suggest alternative selectors when original fails
     * 
     * @param string $screenshotBase64 Current page screenshot
     * @param string $failedSelector The selector that failed
     * @param string $elementDescription What the element should do/look like
     * @return array ['suggestions' => ['#new-selector', '.alternative'], 'reasoning' => 'explanation']
     */
    public function healSelector(string $screenshotBase64, string $failedSelector, string $elementDescription): array
    {
        $prompt = "A test automation selector has failed: \"{$failedSelector}\"
        
The element was described as: \"{$elementDescription}\"

Analyze this screenshot and:
1. Determine if the element still exists (maybe selector changed)
2. Suggest 3 alternative selectors in order of reliability
3. Explain what likely changed

Return ONLY valid JSON:
{
    \"element_exists\": true,
    \"suggestions\": [
        {\"selector\": \"#new-id\", \"type\": \"css\", \"confidence\": 0.9},
        {\"selector\": \"//button[contains(text(), 'Submit')]\", \"type\": \"xpath\", \"confidence\": 0.85},
        {\"selector\": \"Submit\", \"type\": \"text\", \"confidence\": 0.8}
    ],
    \"diagnosis\": \"Button ID changed from 'submit-btn' to 'new-submit-button'\"
}";

        try {
            $response = $this->aiService->analyzeImage($prompt, $screenshotBase64);

            $jsonStart = strpos($response, '{');
            $jsonEnd = strrpos($response, '}');

            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonStr = substr($response, $jsonStart, $jsonEnd - $jsonStart + 1);
                $result = json_decode($jsonStr, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $result;
                }
            }

            return [
                'element_exists' => false,
                'suggestions' => [],
                'diagnosis' => 'Failed to parse AI response'
            ];

        } catch (\Exception $e) {
            Log::error("Selector healing failed: " . $e->getMessage());
            return [
                'element_exists' => false,
                'suggestions' => [],
                'diagnosis' => 'AI service error: ' . $e->getMessage()
            ];
        }
    }
}
