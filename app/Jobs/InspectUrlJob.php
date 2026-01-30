<?php

namespace App\Jobs;

use App\Models\Run;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class InspectUrlJob implements ShouldQueue
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

        $payload = [
            'run_id' => $this->run->id,
            'url' => $project->repo_url, // Assuming repo_url acts as the target URL for now, or add 'url' to project
            'actions' => []
        ];

        try {
            Redis::rpush('qraft:inspector:tasks', json_encode($payload));
            Log::info("Dispatched Run #{$this->run->id} to inspector queue.");
        } catch (\Exception $e) {
            $this->run->update(['status' => 'failed', 'logs' => "Failed to dispatch: " . $e->getMessage()]);
            Log::error("Failed to push to Redis: " . $e->getMessage());
        }
    }
}
