<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Run;
use Illuminate\Support\Facades\DB;

class UsageTracking
{
    /**
     * Track a test run.
     */
    public function trackTestRun(Organization $organization, Run $run): void
    {
        // Test runs are already tracked in the runs table
        // This method can be used for additional tracking or reporting to Stripe

        // If using Stripe metered billing, report usage here
        // $organization->reportUsage('test_runs', 1);
    }

    /**
     * Track an AI generation.
     */
    public function trackAiGeneration(Organization $organization, array $metadata = []): void
    {
        // TODO: Implement AI generation tracking
        // Could use a separate table or increment a counter

        DB::table('usage_events')->insert([
            'organization_id' => $organization->id,
            'event_type' => 'ai_generation',
            'metadata' => json_encode($metadata),
            'created_at' => now(),
        ]);
    }

    /**
     * Get usage summary for current billing period.
     */
    public function getCurrentPeriodUsage(Organization $organization): array
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        return [
            'test_runs' => $organization->runs()
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count(),
            'ai_generations' => $this->getAiGenerationsCount($organization, $startOfMonth, $endOfMonth),
            'storage_used_gb' => $this->getStorageUsage($organization),
            'period_start' => $startOfMonth,
            'period_end' => $endOfMonth,
        ];
    }

    /**
     * Get AI generations count for a period.
     */
    protected function getAiGenerationsCount(Organization $organization, $start, $end): int
    {
        // TODO: Query from usage_events table once implemented
        return 0;
    }

    /**
     * Get storage usage in GB.
     */
    protected function getStorageUsage(Organization $organization): float
    {
        // TODO: Calculate actual storage usage
        // This could include:
        // - Screenshots from test runs
        // - Video recordings
        // - Test artifacts
        return 0.0;
    }

    /**
     * Reset monthly usage counters.
     * This should be called at the start of each billing period.
     */
    public function resetMonthlyCounters(Organization $organization): void
    {
        // If using separate counters table, reset them here
        // For now, we're using time-based queries, so no reset needed
    }

    /**
     * Get usage trend data for charts.
     */
    public function getUsageTrend(Organization $organization, int $days = 30): array
    {
        $startDate = now()->subDays($days);

        $testRuns = $organization->runs()
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        return [
            'test_runs' => $testRuns,
            'period_days' => $days,
        ];
    }
}
