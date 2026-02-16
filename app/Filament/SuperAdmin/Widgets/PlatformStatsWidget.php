<?php

namespace App\Filament\SuperAdmin\Widgets;

use App\Models\Organization;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalOrgs = Organization::count();
        $activeOrgs = Organization::where('subscription_status', 'active')->count();
        $totalUsers = User::count();
        $paidOrgs = Organization::whereIn('subscription_plan', ['pro', 'enterprise'])->count();

        // Calculate MRR (Monthly Recurring Revenue)
        $proOrgs = Organization::where('subscription_plan', 'pro')
            ->where('subscription_status', 'active')
            ->count();
        $enterpriseOrgs = Organization::where('subscription_plan', 'enterprise')
            ->where('subscription_status', 'active')
            ->count();

        $mrr = ($proOrgs * 49) + ($enterpriseOrgs * 299);

        return [
            Stat::make('Total Organizations', $totalOrgs)
                ->description($activeOrgs . ' active')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),

            Stat::make('Total Users', $totalUsers)
                ->description('Platform-wide')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Paid Organizations', $paidOrgs)
                ->description(number_format(($paidOrgs / max($totalOrgs, 1)) * 100, 1) . '% conversion')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),

            Stat::make('Monthly Recurring Revenue', '$' . number_format($mrr))
                ->description('From active subscriptions')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),
        ];
    }
}
