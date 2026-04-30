<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopCityChart extends ChartWidget
{
    protected static ?string $heading = 'Top Lokasi Lowongan';
    protected static ?string $description = 'Daerah dengan jumlah lowongan IT terbanyak';
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 4;
    protected static ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $data = DB::table('jobs_clean')
            ->select('city', DB::raw('COUNT(*) as total'))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $colors = [
            '#3b82f6','#6366f1','#8b5cf6','#a855f7','#ec4899',
            '#f43f5e','#f97316','#f59e0b','#10b981','#06b6d4',
        ];

        return [
            'datasets' => [
                [
                    'label'              => 'Jumlah Lowongan',
                    'data'               => $data->pluck('total')->toArray(),
                    'backgroundColor'    => array_slice($colors, 0, $data->count()),
                    'borderWidth'        => 0,
                    'borderRadius'       => 8,
                    'borderSkipped'      => false,
                    'barPercentage'      => 0.75,
                    'categoryPercentage' => 0.85,
                ],
            ],
            'labels' => $data->pluck('city')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis'   => 'y',
            'interaction' => [
                'mode'      => 'nearest',
                'intersect' => true,
            ],
            'plugins' => [
                'legend'  => ['display' => false],
                'tooltip' => [
                    'enabled'         => true,
                    'backgroundColor' => '#1e293b',
                    'titleColor'      => '#f1f5f9',
                    'bodyColor'       => '#94a3b8',
                    'borderColor'     => '#334155',
                    'borderWidth'     => 1,
                    'padding'         => 12,
                    'displayColors'   => true,
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'grid'        => [
                        'color'   => 'rgba(255,255,255,0.04)',
                        'display' => true,
                    ],
                    'ticks' => [
                        'color'     => '#64748b',
                        'font'      => ['size' => 11],
                        'precision' => 0,
                    ],
                    'border' => ['display' => false],
                ],
                'y' => [
                    'grid'   => ['display' => false],
                    'ticks'  => [
                        'color' => '#e2e8f0',
                        'font'  => ['size' => 12],
                    ],
                    'border' => ['display' => false],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}