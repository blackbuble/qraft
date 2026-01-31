<?php

namespace App\Services;

use App\Models\Organization;
use Illuminate\Support\Facades\Log;

class PlanLimits
{
    /**
     * Check if organization can create a new project.
     */
    public function canCreateProject(Organization $organization): bool
    {
        $limit = $organization->planLimit('projects');

        if ($limit === -1) {
            return true; // unlimited
        }

        return $organization->projects()->count() < $limit;
    }

    /**
     * Check if organization can run a test.
     */
    public function canRunTest(Organization $organization): bool
    {
        $limit = $organization->planLimit('test_runs_per_month');

        if ($limit === -1) {
            return true; // unlimited
        }

        $usage = $this->getMonthlyTestRuns($organization);

        return $usage < $limit;
    }

    /**
     * Check if organization can add a team member.
     */
    public function canAddTeamMember(Organization $organization): bool
    {
        $limit = $organization->planLimit('team_members');

        if ($limit === -1) {
            return true; // unlimited
        }

        return $organization->users()->count() < $limit;
    }

    /**
     * Check if organization can generate AI tests.
     */
    public function canGenerateAiTest(Organization $organization): bool
    {
        $limit = $organization->planLimit('ai_generations_per_month');

        if ($limit === -1) {
            return true; // unlimited
        }

        $usage = $this->getMonthlyAiGenerations($organization);

        return $usage < $limit;
    }

    /**
     * Get monthly test runs count.
     */
    public function getMonthlyTestRuns(Organization $organization): int
    {
        return $organization->runs()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    /**
     * Get monthly AI generations count.
     */
    public function getMonthlyAiGenerations(Organization $organization): int
    {
        // TODO: Track AI generations in a separate table or counter
        // For now, return 0
        return 0;
    }

    /**
     * Get usage percentage for a specific feature.
     */
    public function getUsagePercentage(Organization $organization, string $feature): float
    {
        $limit = $organization->planLimit($feature);

        if ($limit === -1) {
            return 0; // unlimited
        }

        if ($limit === 0) {
            return 100;
        }

        $usage = match ($feature) {
            'projects' => $organization->projects()->count(),
            'test_runs_per_month' => $this->getMonthlyTestRuns($organization),
            'team_members' => $organization->users()->count(),
            'ai_generations_per_month' => $this->getMonthlyAiGenerations($organization),
            default => 0,
        };

        return ($usage / $limit) * 100;
    }

    /**
     * Check if organization is approaching limit (>= 80%).
     */
    public function isApproachingLimit(Organization $organization, string $feature): bool
    {
        return $this->getUsagePercentage($organization, $feature) >= 80;
    }

    /**
     * Get all usage statistics for an organization.
     */
    public function getUsageStats(Organization $organization): array
    {
        $plans = $organization->subscriptionPlans();
        $currentPlan = $organization->subscription_plan ?? 'free';
        $limits = $plans[$currentPlan]['limits'] ?? [];

        $stats = [];

        foreach ($limits as $feature => $limit) {
            $usage = match ($feature) {
                'projects' => $organization->projects()->count(),
                'test_runs_per_month' => $this->getMonthlyTestRuns($organization),
                'team_members' => $organization->users()->count(),
                'ai_generations_per_month' => $this->getMonthlyAiGenerations($organization),
                'storage_gb' => 0, // TODO: Implement storage tracking
                default => 0,
            };

            $stats[$feature] = [
                'usage' => $usage,
                'limit' => $limit,
                'percentage' => $limit === -1 ? 0 : ($limit === 0 ? 100 : ($usage / $limit) * 100),
                'unlimited' => $limit === -1,
            ];
        }

        return $stats;
    }
}
