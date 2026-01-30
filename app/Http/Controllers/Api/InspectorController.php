<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Run;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InspectorController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Simple security check (Should be improved in production)
        if ($request->input('secret') !== 'qraft-internal-secret') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'run_id' => 'required|exists:runs,id',
            'result' => 'required|array',
        ]);

        $run = Run::findOrFail($request->input('run_id'));
        $result = $request->input('result');

        // Handle Artifact Storage
        $settings = app(\App\Settings\StorageSettings::class);

        if (!empty($result['screenshot']) && $settings->store_screenshots) {
            try {
                $disk = $settings->artifact_disk;
                $basePath = trim($settings->artifact_path, '/');
                $filename = "{$basePath}/run_{$run->id}_" . time() . ".jpg";
                $imageContent = base64_decode($result['screenshot']);

                \Illuminate\Support\Facades\Storage::disk($disk)->put($filename, $imageContent, [
                    'visibility' => $settings->visibility
                ]);

                $result['screenshot_path'] = $filename;
                $result['screenshot_disk'] = $disk;
                $result['screenshot_url'] = \Illuminate\Support\Facades\Storage::disk($disk)->url($filename);

                // Remove base64 from result to save DB space
                unset($result['screenshot']);
            } catch (\Exception $e) {
                Log::error("Failed to store screenshot for Run #{$run->id}: " . $e->getMessage());
                // Fallback: keep base64 if storage fails
            }
        }

        $run->update([
            'status' => $result['success'] ? 'completed' : 'failed',
            'result' => $result,
            'completed_at' => now(),
            'logs' => implode("\n", $result['logs'] ?? []),
        ]);

        Log::info("Run #{$run->id} completed via Inspector Webhook.");

        if ($run->status === 'completed') {
            \App\Jobs\AnalyzeRunJob::dispatch($run);

            // Trigger flakiness analysis for this test scenario
            if ($run->testScenario) {
                \App\Jobs\AnalyzeFlakinessJob::dispatch($run->testScenario);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
