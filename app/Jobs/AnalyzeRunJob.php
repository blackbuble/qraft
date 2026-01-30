<?php

namespace App\Jobs;

use App\Models\Run;
use App\Services\AiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeRunJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $run;

    /**
     * Create a new job instance.
     */
    public function __construct(Run $run)
    {
        $this->run = $run;
    }

    /**
     * Execute the job.
     */
    public function handle(AiService $aiService, \App\Settings\StorageSettings $storageSettings): void
    {
        $result = $this->run->result;
        $screenshotBase64 = null;

        if (!empty($result['screenshot_path'])) {
            try {
                $disk = $result['screenshot_disk'] ?? $storageSettings->artifact_disk;
                $imageContent = \Illuminate\Support\Facades\Storage::disk($disk)->get($result['screenshot_path']);
                $screenshotBase64 = base64_encode($imageContent);
            } catch (\Exception $e) {
                Log::error("Failed to retrieve screenshot from storage: " . $e->getMessage());
            }
        } elseif (!empty($result['screenshot'])) {
            $screenshotBase64 = $result['screenshot'];
        }

        if (empty($screenshotBase64)) {
            Log::warning("Run #{$this->run->id} has no screenshot to analyze.");
            return;
        }

        try {
            $technicalContext = "## Console Logs\n" . ($this->run->logs ?: 'None') . "\n\n";
            $networkErrors = !empty($result['network_errors']) ? json_encode($result['network_errors'], JSON_PRETTY_PRINT) : 'None';
            $technicalContext .= "## Network Errors\n" . $networkErrors;

            $prompt = "Act as a Senior QA Automation Engineer and Technical Lead. Review this screenshot of a web application and the provided technical metadata (network logs and console errors). 
            
            Identify any visual bugs, layout regressions, broken images, or suspicious error messages. 
            Crucially, cross-reference visual failures with technical logs to explain the root cause.
            
            TECHNICAL CONTEXT:
            {$technicalContext}
            
            Return ONLY a valid JSON object with the following structure:
            {
                \"severity\": \"critical\" | \"major\" | \"minor\" | \"none\",
                \"summary\": \"Brief executive summary of findings\",
                \"issues\": [\"List of specific issues found\"],
                \"technical_rca\": \"Detailed explanation of the technical cause if applicable (e.g., 'API 401 caused login button to fail')\"
            }";

            $analysis = $aiService->analyzeImage($prompt, $screenshotBase64);

            // Attempt to parse JSON
            $jsonStart = strpos($analysis, '{');
            $jsonEnd = strrpos($analysis, '}');

            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonStr = substr($analysis, $jsonStart, $jsonEnd - $jsonStart + 1);
                $analysisData = json_decode($jsonStr, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->run->update(['severity' => $analysisData['severity'] ?? 'minor']);
                    // Store structured data back formatted as markdown for compatibility or keep raw
                    $result['ai_analysis_json'] = $analysisData;

                    // Keep human readable breakdown for the text field
                    $analysis = "**Severity: " . ucfirst($analysisData['severity']) . "**\n\n" .
                        $analysisData['summary'];

                    if (!empty($analysisData['technical_rca'])) {
                        $analysis .= "\n\n**Technical Root Cause:**\n" . $analysisData['technical_rca'];
                    }

                    $analysis .= "\n\n**Issues:**\n- " . implode("\n- ", $analysisData['issues']);
                }
            }

            $result['ai_analysis'] = $analysis;

            $this->run->update(['result' => $result]);

            Log::info("Run #{$this->run->id} analyzed successfully by AI.");

            // Notify stakeholders if Critical or Major issues found
            if (in_array($this->run->severity, ['critical', 'major'])) {
                $emails = $this->run->project->notification_emails;
                if (!empty($emails)) {
                    \Illuminate\Support\Facades\Notification::route('mail', $emails)
                        ->notify(new \App\Notifications\TestRunFailed($this->run));

                    Log::info("Notification sent to stakeholders for Run #{$this->run->id}");
                }
            }

        } catch (\Exception $e) {
            Log::error("AI Analysis failed for Run #{$this->run->id}: " . $e->getMessage());

            $result['ai_analysis'] = "AI Analysis Failed: " . $e->getMessage();
            $this->run->update(['result' => $result]);
        }
    }
}
