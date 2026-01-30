<?php

namespace App\Filament\Widgets;

use App\Models\Run;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PriorityChart extends ChartWidget
{
    protected static ?string $heading = 'Runs by Priority';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $data = Run::query()
            ->join('test_scenarios', 'runs.test_scenario_id', '=', 'test_scenarios.id')
            ->select('test_scenarios.priority', DB::raw('count(*) as count'))
            ->groupBy('test_scenarios.priority')
            ->pluck('count', 'test_scenarios.priority');

        $colors = [
            'critical' => '#ef4444', // Red-500
            'high' => '#f97316',     // Orange-500
            'medium' => '#3b82f6',   // Blue-500
            'low' => '#9ca3af',      // Gray-400
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Priority',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => $data->keys()->map(fn($priority) => $colors[$priority] ?? '#cbd5e1')->toArray(),
                    'borderWidth' => 0,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => $data->keys()->map(fn($val) => ucfirst($val))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'cutout' => '60%',
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
