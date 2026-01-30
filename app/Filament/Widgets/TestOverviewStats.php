<?php

namespace App\Filament\Widgets;

use App\Models\Run;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class TestOverviewStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $filter = $this->filters['filter'] ?? 'all';

        $query = Run::query();

        if ($filter === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($filter === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } elseif ($filter === 'year') {
            $query->whereYear('created_at', now()->year);
        }

        // Clone query for efficiency
        $total = (clone $query)->count();
        $success = (clone $query)->where('status', 'completed')->count();
        $failed = (clone $query)->where('status', 'failed')->count();
        $running = (clone $query)->where('status', 'running')->count();

        return [
            Stat::make('Total Tests', $total)
                ->description('Filtered by selected period')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('primary'),
            Stat::make('Successful', $success)
                ->description('Tests passed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Failed', $failed)
                ->description('Tests failed')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            Stat::make('Running', $running)
                ->description('Currently executing')
                ->descriptionIcon('heroicon-m-play-circle')
                ->color('warning'),
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => 'All Time',
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
            'year' => 'This Year',
        ];
    }
}
