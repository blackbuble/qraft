<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Run;

class SeverityChart extends ChartWidget
{
    protected static ?string $heading = 'Issues by Severity';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Run::select('severity', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->whereNotNull('severity')
            ->groupBy('severity')
            ->pluck('count', 'severity');

        $colors = [
            'critical' => '#ef4444', // Red-500
            'major' => '#f97316',    // Orange-500
            'minor' => '#eab308',    // Yellow-500
            'none' => '#22c55e',     // Green-500
            'low' => '#22c55e',      // Green-500 (fallback)
            'medium' => '#eab308',   // Yellow-500 (fallback)
            'high' => '#ef4444',     // Red-500 (fallback)
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Severity',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => $data->keys()->map(fn($severity) => $colors[strtolower($severity)] ?? '#cbd5e1')->toArray(), // Gray-400 fallback
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
            'cutout' => '75%', // Thinner doughnut
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'font' => [
                            'family' => 'inherit',
                        ],
                        'usePointStyle' => true,
                    ],
                ],
            ],
        ];
    }
}
