<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Log;

class AiTestGeneratorService
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Generate complete test scenario from natural language requirement
     * 
     * @param string $requirement Natural language description
     * @param Project $project Project context for base URL
     * @return array Test steps with enhanced metadata
     */
    public function generateFromRequirement(string $requirement, Project $project): array
    {
        $baseUrl = $project->repo_url ?? 'http://example.com';

        $prompt = $this->buildPrompt($requirement, $baseUrl);

        try {
            $response = $this->aiService->analyze($prompt);
            $steps = $this->parseSteps($response);

            // Validate and enhance steps
            $steps = $this->validateSteps($steps);
            $steps = $this->enhanceSteps($steps, $baseUrl);

            return $steps;

        } catch (\Exception $e) {
            Log::error("AI Test Generation failed: " . $e->getMessage());
            throw new \Exception("Failed to generate test: " . $e->getMessage());
        }
    }

    /**
     * Build comprehensive prompt for AI
     */
    protected function buildPrompt(string $requirement, string $baseUrl): string
    {
        return <<<EOT
You are an expert QA automation engineer. Generate a comprehensive test scenario from this requirement:

**Requirement**: "$requirement"

**Base URL**: $baseUrl

**Available Actions**:
1. visit - Navigate to URL
   Example: {"action": "visit", "value": "/login"}

2. click - Click element
   Example: {"action": "click", "selector": "#submit-btn", "selector_type": "css"}

3. type - Type text into input
   Example: {"action": "type", "selector": "#email", "selector_type": "css", "value": "user@example.com"}

4. hover - Hover over element
   Example: {"action": "hover", "selector": ".dropdown", "selector_type": "css"}

5. select - Select dropdown option
   Example: {"action": "select", "selector": "#country", "selector_type": "css", "value": "USA"}

6. check/uncheck - Toggle checkbox
   Example: {"action": "check", "selector": "#terms", "selector_type": "css"}

7. wait - Wait for milliseconds
   Example: {"action": "wait", "value": "2000"}

8. assert_text - Verify text content
   Example: {"action": "assert_text", "selector": ".alert", "selector_type": "css", "value": "Success"}

9. assert_visible - Verify element is visible
   Example: {"action": "assert_visible", "selector": "#modal", "selector_type": "css"}

**Selector Types**: css, xpath, text, role, testid, placeholder, label, ai_describe

**Instructions**:
1. Return ONLY a valid JSON array of steps
2. Use descriptive selectors (prefer IDs > classes > tags)
3. Add "description" field to each step explaining what it does
4. Use relative URLs (e.g., "/login" not full URL)
5. Include assertions to verify success
6. Use realistic test data (emails, names, etc.)
7. Add waits where necessary (after clicks, before assertions)

**Output Format**:
[
    {
        "action": "visit",
        "value": "/login",
        "description": "Navigate to login page"
    },
    {
        "action": "type",
        "selector": "#email",
        "selector_type": "css",
        "value": "test@example.com",
        "description": "Enter email address"
    }
]

Return ONLY the JSON array, no markdown formatting, no explanations.
EOT;
    }

    /**
     * Parse AI response and extract JSON steps
     */
    protected function parseSteps(string $response): array
    {
        // Remove markdown code blocks if present
        $cleaned = preg_replace('/```json\s*|\s*```/', '', $response);
        $cleaned = trim($cleaned);

        // Try to find JSON array
        $jsonStart = strpos($cleaned, '[');
        $jsonEnd = strrpos($cleaned, ']');

        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonStr = substr($cleaned, $jsonStart, $jsonEnd - $jsonStart + 1);
            $steps = json_decode($jsonStr, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($steps)) {
                return $steps;
            }
        }

        // Fallback: try parsing entire response
        $steps = json_decode($cleaned, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($steps)) {
            return $steps;
        }

        throw new \Exception("Failed to parse AI response as JSON: " . json_last_error_msg());
    }

    /**
     * Validate steps structure
     */
    protected function validateSteps(array $steps): array
    {
        $validActions = ['visit', 'click', 'type', 'hover', 'select', 'check', 'uncheck', 'wait', 'assert_text', 'assert_visible'];
        $validSelectorTypes = ['css', 'xpath', 'text', 'role', 'testid', 'placeholder', 'label', 'ai_describe'];

        $validated = [];

        foreach ($steps as $step) {
            if (!isset($step['action']) || !in_array($step['action'], $validActions)) {
                Log::warning("Invalid action in step: " . json_encode($step));
                continue;
            }

            // Ensure selector_type is valid
            if (isset($step['selector_type']) && !in_array($step['selector_type'], $validSelectorTypes)) {
                $step['selector_type'] = 'css'; // Default to CSS
            }

            // Set default selector_type if missing
            if (isset($step['selector']) && !isset($step['selector_type'])) {
                $step['selector_type'] = 'css';
            }

            $validated[] = $step;
        }

        return $validated;
    }

    /**
     * Enhance steps with additional metadata
     */
    protected function enhanceSteps(array $steps, string $baseUrl): array
    {
        $enhanced = [];

        foreach ($steps as $step) {
            // Add default description if missing
            if (!isset($step['description'])) {
                $step['description'] = $this->generateDescription($step);
            }

            $enhanced[] = $step;
        }

        return $enhanced;
    }

    /**
     * Generate human-readable description for step
     */
    protected function generateDescription(array $step): string
    {
        $action = $step['action'];

        switch ($action) {
            case 'visit':
                return "Navigate to " . ($step['value'] ?? 'page');
            case 'click':
                return "Click " . ($step['selector'] ?? 'element');
            case 'type':
                return "Enter text into " . ($step['selector'] ?? 'field');
            case 'hover':
                return "Hover over " . ($step['selector'] ?? 'element');
            case 'select':
                return "Select option from " . ($step['selector'] ?? 'dropdown');
            case 'check':
                return "Check " . ($step['selector'] ?? 'checkbox');
            case 'uncheck':
                return "Uncheck " . ($step['selector'] ?? 'checkbox');
            case 'wait':
                return "Wait " . ($step['value'] ?? '1000') . "ms";
            case 'assert_text':
                return "Verify text: " . ($step['value'] ?? '');
            case 'assert_visible':
                return "Verify " . ($step['selector'] ?? 'element') . " is visible";
            default:
                return ucfirst($action);
        }
    }

    /**
     * Regenerate specific steps based on feedback
     */
    public function regenerateSteps(array $currentSteps, string $feedback): array
    {
        $prompt = "The following test steps were generated but need improvement:\n\n";
        $prompt .= json_encode($currentSteps, JSON_PRETTY_PRINT);
        $prompt .= "\n\nUser Feedback: $feedback\n\n";
        $prompt .= "Please regenerate the steps addressing the feedback. Return ONLY the JSON array.";

        $response = $this->aiService->analyze($prompt);
        return $this->parseSteps($response);
    }
}
