<?php

namespace App\Services;

use App\Models\TestScenario;
use App\Models\Run;
use Illuminate\Support\Facades\Storage;

class DocumentationGeneratorService
{
    /**
     * Generate markdown documentation from test scenario
     */
    public function generateMarkdown(TestScenario $scenario): string
    {
        $markdown = "# {$scenario->title}\n\n";
        $markdown .= "> {$scenario->description}\n\n";
        $markdown .= "---\n\n";

        // Get latest successful run for screenshots
        $latestRun = $scenario->runs()
            ->where('status', 'completed')
            ->where('severity', '!=', 'critical')
            ->latest()
            ->first();

        $stepNumber = 1;

        foreach ($scenario->steps as $step) {
            $markdown .= $this->formatStep($step, $stepNumber, $latestRun);
            $stepNumber++;
        }

        $markdown .= "\n---\n\n";
        $markdown .= "*This documentation was auto-generated from test scenario `{$scenario->title}` and is always up-to-date.*\n\n";
        $markdown .= "**Last updated**: " . now()->format('F j, Y g:i A') . "\n\n";
        $markdown .= "**Powered by QRAFT** - Quality Intelligence Platform\n";

        return $markdown;
    }

    /**
     * Format individual step as markdown
     */
    protected function formatStep(array $step, int $number, ?Run $run): string
    {
        $description = $step['description'] ?? $this->generateDescription($step);
        $md = "## Step {$number}: {$description}\n\n";

        switch ($step['action']) {
            case 'visit':
                $md .= "Navigate to: `{$step['value']}`\n\n";
                $md .= "```\nURL: {$step['value']}\n```\n\n";
                break;

            case 'click':
                $md .= "Click the element matching selector: `{$step['selector']}`\n\n";
                $md .= "**Selector type**: {$step['selector_type']}\n\n";
                break;

            case 'type':
                $md .= "Enter the following text into the field matching `{$step['selector']}`:\n\n";
                $md .= "```\n{$step['value']}\n```\n\n";
                break;

            case 'check':
                $md .= "Check the checkbox matching: `{$step['selector']}`\n\n";
                break;

            case 'uncheck':
                $md .= "Uncheck the checkbox matching: `{$step['selector']}`\n\n";
                break;

            case 'select':
                $md .= "Select option `{$step['value']}` from dropdown: `{$step['selector']}`\n\n";
                break;

            case 'assert_text':
                $md .= "Verify that element `{$step['selector']}` contains the text:\n\n";
                $md .= "**Expected**: \"{$step['value']}\"\n\n";
                break;

            case 'assert_visible':
                $md .= "Verify that element `{$step['selector']}` is visible on the page.\n\n";
                break;

            case 'assert_url':
                $md .= "Verify that the current URL matches: `{$step['value']}`\n\n";
                break;

            case 'wait':
                $waitTime = ($step['value'] / 1000);
                $md .= "Wait for {$waitTime} seconds.\n\n";
                break;

            case 'screenshot':
                $md .= "Take a screenshot at this point.\n\n";
                break;

            default:
                $md .= "Perform action: `{$step['action']}`\n\n";
                if (isset($step['selector'])) {
                    $md .= "Target: `{$step['selector']}`\n\n";
                }
                if (isset($step['value'])) {
                    $md .= "Value: `{$step['value']}`\n\n";
                }
        }

        // Add screenshot if available
        if ($run && isset($run->result['screenshot_url'])) {
            $md .= "![Step {$number} Screenshot]({$run->result['screenshot_url']})\n\n";
        }

        // Add note if selector type is special
        if (isset($step['selector_type']) && $step['selector_type'] !== 'css') {
            $md .= "> **Note**: This step uses `{$step['selector_type']}` selector type.\n\n";
        }

        return $md;
    }

    /**
     * Generate description from step if not provided
     */
    protected function generateDescription(array $step): string
    {
        $action = ucfirst($step['action']);

        switch ($step['action']) {
            case 'visit':
                return "Navigate to {$step['value']}";
            case 'click':
                return "Click element";
            case 'type':
                return "Enter text";
            case 'assert_text':
                return "Verify text content";
            case 'assert_visible':
                return "Verify element visibility";
            case 'assert_url':
                return "Verify URL";
            case 'wait':
                return "Wait for page";
            default:
                return $action;
        }
    }

    /**
     * Generate HTML documentation
     */
    public function generateHtml(TestScenario $scenario): string
    {
        $markdown = $this->generateMarkdown($scenario);

        // Convert markdown to HTML using league/commonmark
        $converter = new \League\CommonMark\CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $html = $converter->convert($markdown)->getContent();

        // Wrap in template
        return $this->wrapInTemplate($scenario->title, $html);
    }

    /**
     * Wrap HTML content in template
     */
    protected function wrapInTemplate(string $title, string $content): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} - Documentation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            padding: 2rem;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #1a1a1a;
            margin-bottom: 1rem;
            font-size: 2.5rem;
            border-bottom: 3px solid #f59e0b;
            padding-bottom: 0.5rem;
        }
        
        h2 {
            color: #2d3748;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        blockquote {
            border-left: 4px solid #f59e0b;
            padding-left: 1rem;
            margin: 1rem 0;
            color: #666;
            font-style: italic;
        }
        
        code {
            background: #f7fafc;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 0.9em;
            color: #e53e3e;
        }
        
        pre {
            background: #2d3748;
            color: #f7fafc;
            padding: 1rem;
            border-radius: 6px;
            overflow-x: auto;
            margin: 1rem 0;
        }
        
        pre code {
            background: transparent;
            color: #f7fafc;
            padding: 0;
        }
        
        img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin: 1rem 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        hr {
            border: none;
            border-top: 2px solid #e2e8f0;
            margin: 2rem 0;
        }
        
        strong {
            color: #1a1a1a;
        }
        
        .footer {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #718096;
            font-size: 0.9rem;
        }
        
        .footer a {
            color: #f59e0b;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        {$content}
        
        <div class="footer">
            <p>Generated with <a href="https://qraft.io" target="_blank">QRAFT</a> - Quality Intelligence Platform</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Save documentation to storage
     */
    public function saveToStorage(TestScenario $scenario, string $format = 'md'): string
    {
        $content = $format === 'html'
            ? $this->generateHtml($scenario)
            : $this->generateMarkdown($scenario);

        $filename = "docs/" . \Str::slug($scenario->title) . "-{$scenario->id}.{$format}";

        Storage::put($filename, $content);

        return $filename;
    }

    /**
     * Generate and download documentation
     */
    public function download(TestScenario $scenario, string $format = 'md'): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filename = $this->saveToStorage($scenario, $format);

        return Storage::download($filename, \Str::slug($scenario->title) . ".{$format}");
    }
}
