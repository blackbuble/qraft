<?php

namespace App\Filament\Widgets;

use App\Services\PlanLimits;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class UsageStatsWidget extends Widget
{
    protected static string $view = 'filament.widgets.usage-stats-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -1;

    public function getUsageStats(): array
    {
        $organization = Filament::getTenant();
        $planLimits = app(PlanLimits::class);

        return $planLimits->getUsageStats($organization);
    }

    public function shouldShowUpgradePrompt(): bool
    {
        $organization = Filament::getTenant();

        if ($organization->subscription_plan === 'enterprise') {
            return false; // Already on highest plan
        }

        $planLimits = app(PlanLimits::class);
        $stats = $planLimits->getUsageStats($organization);

        // Show upgrade prompt if any feature is at >= 80% usage
        foreach ($stats as $stat) {
            if (!$stat['unlimited'] && $stat['percentage'] >= 80) {
                return true;
            }
        }

        return false;
    }

    public function getRecommendedPlan(): string
    {
        $organization = Filament::getTenant();

        if ($organization->subscription_plan === 'free') {
            return 'pro';
        }

        return 'enterprise';
    }
}
