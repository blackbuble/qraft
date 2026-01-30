<?php

namespace App\Jobs;

use App\Models\Run;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ExecuteTestJob implements ShouldQueue
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
    public function handle(): void
    {
        $this->run->update(['status' => 'processing', 'started_at' => now()]);

        $project = $this->run->project;
        $scenario = $this->run->testScenario;

        $steps = [];

        if ($scenario && !empty($scenario->steps)) {
            $steps = array_map(function ($step) use ($project) {
                if (($step['action'] === 'visit') && str_starts_with($step['value'], '/')) {
                    $baseUrl = rtrim($project->repo_url ?? 'http://example.com', '/');
                    $step['value'] = $baseUrl . $step['value'];
                }
                return $step;
            }, $scenario->steps);
        } else {
            // Legacy/Default Fallback
            $steps = [
                [
                    'action' => 'visit',
                    'value' => $project->repo_url ?? 'http://example.com'
                ]
            ];
        }

        $payload = [
            'run_id' => $this->run->id,
            'steps' => $steps,
            'network_mocks' => $scenario?->network_mocks ?? []
        ];

        try {
            Redis::rpush('qraft:inspector:tasks', json_encode($payload));
            Log::info("Dispatched Run #{$this->run->id} to inspector queue with " . count($steps) . " steps.");
        } catch (\Exception $e) {
            $this->run->update(['status' => 'failed', 'logs' => "Failed to dispatch: " . $e->getMessage()]);
            Log::error("Failed to push to Redis: " . $e->getMessage());
        }
    }
}
