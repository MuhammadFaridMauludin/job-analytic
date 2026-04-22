<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopCompanyChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Companies';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = DB::table('jobs_clean')
            ->select('company', DB::raw('COUNT(*) as total'))
            ->groupBy('company')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $colors = [
            'rgba(99, 102, 241, 0.85)',
            'rgba(139, 92, 246, 0.85)',
            'rgba(236, 72, 153, 0.85)',
            'rgba(59, 130, 246, 0.85)',
            'rgba(16, 185, 129, 0.85)',
            'rgba(245, 158, 11, 0.85)',
            'rgba(239, 68, 68, 0.85)',
            'rgba(20, 184, 166, 0.85)',
            'rgba(249, 115, 22, 0.85)',
            'rgba(168, 85, 247, 0.85)',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Job Listings',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => $colors,
                    'borderColor' => array_map(fn($c) => str_replace('0.85', '1', $c), $colors),
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                ],
            ],
            'labels' => $data->pluck('company')->map(fn($name) => \Illuminate\Support\Str::limit($name, 20)),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
                'tooltip' => [
                    'callbacks' => [],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(255,255,255,0.05)',
                    ],
                    'ticks' => [
                        'stepSize' => 5,
                        'color' => 'rgba(255,255,255,0.5)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'color' => 'rgba(255,255,255,0.7)',
                        'font' => ['size' => 10],
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}