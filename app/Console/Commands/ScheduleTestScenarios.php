<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScheduleTestScenarios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qraft:schedule-tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run scheduled test scenarios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Checking for scheduled test scenarios...");

        $scenarios = \App\Models\TestScenario::where('is_active', true)
            ->where('frequency', '!=', 'manual')
            ->get();

        foreach ($scenarios as $scenario) {
            if ($this->shouldRun($scenario)) {
                $this->info("Running scenario: {$scenario->title}");
                $this->dispatchTest($scenario);
            }
        }

        $this->info("Done.");
    }

    protected function shouldRun($scenario)
    {
        if (!$scenario->last_run_at) {
            return true;
        }

        $now = now();
        $lastRun = $scenario->last_run_at;

        return match ($scenario->frequency) {
            'hourly' => $lastRun->diffInHours($now) >= 1,
            'daily' => $lastRun->diffInHours($now) >= 24,
            'weekly' => $lastRun->diffInDays($now) >= 7,
            default => false,
        };
    }

    protected function dispatchTest($scenario)
    {
        // Find default agent for project (or first available)
        $agent = $scenario->project->agents->first();

        if (!$agent) {
            $this->error("No agent found for project: {$scenario->project->name}");
            return;
        }

        $run = \App\Models\Run::create([
            'project_id' => $scenario->project_id,
            'agent_id' => $agent->id,
            'test_scenario_id' => $scenario->id,
            'status' => 'queued',
        ]);

        \App\Jobs\ExecuteTestJob::dispatch($run);

        $scenario->update(['last_run_at' => now()]);

        $this->info("Dispatched Run #{$run->id}");
    }
}
