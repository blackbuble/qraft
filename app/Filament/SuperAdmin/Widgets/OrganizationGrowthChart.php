<?php

namespace App\Filament\SuperAdmin\Widgets;

use App\Models\Organization;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrganizationGrowthChart extends ChartWidget
{
    protected static ?string $heading = 'Organization Growth';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Organization::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $values = [];
        $cumulative = 0;

        foreach ($data as $item) {
            $labels[] = $item->date;
            $cumulative += $item->count;
            $values[] = $cumulative;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Organizations',
                    'data' => $values,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
