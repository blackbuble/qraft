<?php

namespace App\Jobs;

use App\Models\TestScenario;
use App\Models\TestFlakiness;
use App\Services\AiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeFlakinessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $testScenario;

    public function __construct(TestScenario $testScenario)
    {
        $this->testScenario = $testScenario;
    }

    public function handle()
    {
        $runs = $this->testScenario->runs()
            ->latest()
            ->limit(50)
            ->get();

        if ($runs->count() < 5) {
            Log::info("TestScenario #{$this->testScenario->id}: Not enough runs for flakiness analysis");
            return;
        }

        // Calculate flakiness metrics
        $metrics = $this->calculateMetrics($runs);

        // Detect patterns
        $pattern = $this->detectPattern($runs);

        // AI diagnosis (only if flaky)
        $diagnosis = null;
        if ($metrics['score'] > 20) {
            $diagnosis = $this->getAiDiagnosis($runs, $pattern, $metrics);
        }

        // Save or update flakiness record
        TestFlakiness::updateOrCreate(
            ['test_scenario_id' => $this->testScenario->id],
            [
                'flakiness_score' => $metrics['score'],
                'total_runs' => $metrics['total'],
                'pass_count' => $metrics['pass'],
                'fail_count' => $metrics['fail'],
                'transition_count' => $metrics['transitions'],
                'pattern' => $pattern,
                'last_analyzed_at' => now(),
                'ai_diagnosis' => $diagnosis['diagnosis'] ?? null,
                'suggested_fix' => $diagnosis['fix'] ?? null,
            ]
        );

        Log::info("TestScenario #{$this->testScenario->id}: Flakiness score = {$metrics['score']}%");
    }

    protected function calculateMetrics($runs)
    {
        $total = $runs->count();
        $pass = $runs->where('status', 'completed')->where('severity', '!=', 'critical')->count();
        $fail = $total - $pass;

        // Count transitions (pass→fail or fail→pass)
        $transitions = 0;
        $previousStatus = null;

        foreach ($runs as $run) {
            $currentStatus = ($run->status === 'completed' && $run->severity !== 'critical');

            if ($previousStatus !== null && $currentStatus !== $previousStatus) {
                $transitions++;
            }

            $previousStatus = $currentStatus;
        }

        // Flakiness score calculation
        // More transitions = more flaky
        // Also consider pass/fail ratio
        $transitionScore = min(100, ($transitions / $total) * 100);
        $ratioScore = min(100, (min($pass, $fail) / $total) * 200); // Peaks at 50/50 ratio

        $score = round(($transitionScore * 0.7) + ($ratioScore * 0.3));

        return [
            'score' => $score,
            'total' => $total,
            'pass' => $pass,
            'fail' => $fail,
            'transitions' => $transitions,
        ];
    }

    protected function detectPattern($runs)
    {
        $pattern = [
            'time_based' => $this->detectTimePattern($runs),
            'day_based' => $this->detectDayPattern($runs),
            'sequence' => $this->getSequence($runs),
            'failure_rate_trend' => $this->getFailureRateTrend($runs),
        ];

        return $pattern;
    }

    protected function detectTimePattern($runs)
    {
        // Detect if failures happen at specific hours
        $failuresByHour = [];
        $totalByHour = [];

        foreach ($runs as $run) {
            $hour = $run->created_at->format('H');
            $totalByHour[$hour] = ($totalByHour[$hour] ?? 0) + 1;

            if ($run->status !== 'completed' || $run->severity === 'critical') {
                $failuresByHour[$hour] = ($failuresByHour[$hour] ?? 0) + 1;
            }
        }

        if (empty($failuresByHour)) {
            return null;
        }

        // Calculate failure rate per hour
        $failureRates = [];
        foreach ($failuresByHour as $hour => $failures) {
            $failureRates[$hour] = ($failures / $totalByHour[$hour]) * 100;
        }

        // Find peak failure hour
        arsort($failureRates);
        $peakHour = array_key_first($failureRates);
        $peakRate = $failureRates[$peakHour];

        // Only report if significantly higher than average
        $avgRate = array_sum($failureRates) / count($failureRates);
        if ($peakRate > $avgRate * 1.5) {
            return [
                'type' => 'time_based',
                'peak_hour' => $peakHour,
                'peak_rate' => round($peakRate, 1),
                'description' => "Failures peak at {$peakHour}:00 ({$peakRate}% failure rate)"
            ];
        }

        return null;
    }

    protected function detectDayPattern($runs)
    {
        // Detect if failures happen on specific days of week
        $failuresByDay = [];
        $totalByDay = [];

        foreach ($runs as $run) {
            $day = $run->created_at->format('l'); // Monday, Tuesday, etc.
            $totalByDay[$day] = ($totalByDay[$day] ?? 0) + 1;

            if ($run->status !== 'completed' || $run->severity === 'critical') {
                $failuresByDay[$day] = ($failuresByDay[$day] ?? 0) + 1;
            }
        }

        if (empty($failuresByDay)) {
            return null;
        }

        // Calculate failure rate per day
        $failureRates = [];
        foreach ($failuresByDay as $day => $failures) {
            $failureRates[$day] = ($failures / $totalByDay[$day]) * 100;
        }

        arsort($failureRates);
        $peakDay = array_key_first($failureRates);
        $peakRate = $failureRates[$peakDay];

        $avgRate = array_sum($failureRates) / count($failureRates);
        if ($peakRate > $avgRate * 1.5) {
            return [
                'type' => 'day_based',
                'peak_day' => $peakDay,
                'peak_rate' => round($peakRate, 1),
                'description' => "Failures peak on {$peakDay} ({$peakRate}% failure rate)"
            ];
        }

        return null;
    }

    protected function getSequence($runs)
    {
        // Get last 10 runs as ✓/✗ sequence
        $sequence = $runs->take(10)->map(function ($run) {
            return ($run->status === 'completed' && $run->severity !== 'critical') ? '✓' : '✗';
        })->join('');

        return $sequence;
    }

    protected function getFailureRateTrend($runs)
    {
        // Split runs into first half and second half
        $midpoint = (int) ($runs->count() / 2);
        $firstHalf = $runs->slice(0, $midpoint);
        $secondHalf = $runs->slice($midpoint);

        $firstHalfFailures = $firstHalf->filter(function ($run) {
            return $run->status !== 'completed' || $run->severity === 'critical';
        })->count();

        $secondHalfFailures = $secondHalf->filter(function ($run) {
            return $run->status !== 'completed' || $run->severity === 'critical';
        })->count();

        $firstRate = ($firstHalfFailures / $firstHalf->count()) * 100;
        $secondRate = ($secondHalfFailures / $secondHalf->count()) * 100;

        if ($secondRate > $firstRate * 1.3) {
            return 'worsening';
        } elseif ($secondRate < $firstRate * 0.7) {
            return 'improving';
        } else {
            return 'stable';
        }
    }

    protected function getAiDiagnosis($runs, $pattern, $metrics)
    {
        $aiService = app(AiService::class);

        $prompt = "Analyze this flaky test and provide diagnosis:\n\n";
        $prompt .= "**Test**: {$this->testScenario->title}\n";
        $prompt .= "**Flakiness Score**: {$metrics['score']}%\n";
        $prompt .= "**Pass/Fail Ratio**: {$metrics['pass']}/{$metrics['fail']} (out of {$metrics['total']} runs)\n";
        $prompt .= "**Transitions**: {$metrics['transitions']}\n";
        $prompt .= "**Recent Pattern**: {$pattern['sequence']}\n\n";

        if ($pattern['time_based']) {
            $prompt .= "**Time Pattern**: {$pattern['time_based']['description']}\n";
        }

        if ($pattern['day_based']) {
            $prompt .= "**Day Pattern**: {$pattern['day_based']['description']}\n";
        }

        $prompt .= "**Trend**: {$pattern['failure_rate_trend']}\n\n";

        $prompt .= "**Recent Failures**:\n";
        $failedRuns = $runs->filter(function ($run) {
            return $run->status !== 'completed' || $run->severity === 'critical';
        })->take(3);

        foreach ($failedRuns as $run) {
            $prompt .= "- {$run->created_at->format('Y-m-d H:i')}: {$run->ai_analysis}\n";
        }

        $prompt .= "\n**Task**: Based on this data, provide:\n";
        $prompt .= "1. Root cause diagnosis (what's causing the flakiness?)\n";
        $prompt .= "2. Suggested fix (how to make it more reliable?)\n\n";
        $prompt .= "Return ONLY valid JSON:\n";
        $prompt .= '{"diagnosis": "brief diagnosis", "fix": "actionable fix suggestion"}';

        try {
            $response = $aiService->analyze($prompt);

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
        } catch (\Exception $e) {
            Log::error("AI diagnosis failed: " . $e->getMessage());
        }

        // Fallback diagnosis based on patterns
        return $this->getFallbackDiagnosis($pattern, $metrics);
    }

    protected function getFallbackDiagnosis($pattern, $metrics)
    {
        $diagnosis = "Test shows flaky behavior with {$metrics['transitions']} transitions between pass/fail states. ";
        $fix = "";

        if ($pattern['time_based']) {
            $diagnosis .= $pattern['time_based']['description'] . ". This suggests a time-dependent issue. ";
            $fix = "Investigate scheduled tasks, cron jobs, or server maintenance that might occur at {$pattern['time_based']['peak_hour']}:00. ";
        }

        if ($pattern['day_based']) {
            $diagnosis .= $pattern['day_based']['description'] . ". This suggests a weekly pattern. ";
            $fix .= "Check for weekly deployments, backups, or increased load on {$pattern['day_based']['peak_day']}. ";
        }

        if ($pattern['failure_rate_trend'] === 'worsening') {
            $diagnosis .= "Failure rate is worsening over time. ";
            $fix .= "Recent code changes may have introduced race conditions or timing issues. ";
        }

        if (empty($fix)) {
            $fix = "Add explicit waits, use more stable selectors, or check for race conditions in the test steps.";
        }

        return [
            'diagnosis' => trim($diagnosis),
            'fix' => trim($fix)
        ];
    }
}
