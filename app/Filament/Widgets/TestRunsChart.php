<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Run;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TestRunsChart extends ChartWidget
{
    protected static ?string $heading = 'Runs Over Time';

    protected function getData(): array
    {
        $data = Trend::model(Run::class)
            ->between(
                start: now()->subDays(7),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Test Runs',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'fill' => 'start',
                    'borderColor' => '#6366f1', // Indigo-500
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)', // Indigo-500 with opacity
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'grid' => [
                        'display' => false, // Cleaner look
                        'borderDash' => [5, 5],
                    ],
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
