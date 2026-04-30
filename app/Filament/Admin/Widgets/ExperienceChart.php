<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ExperienceChart extends ChartWidget
{
    protected static ?string $heading = 'Experience Level Distribution';
    protected static ?string $description = 'Proporsi lowongan berdasarkan level pengalaman';
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 4;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = DB::table('jobs_clean')
            ->select('experience_level', DB::raw('COUNT(*) as total'))
            ->whereNotNull('experience_level')
            ->where('experience_level', '!=', '')
            ->groupBy('experience_level')
            ->orderByDesc('total')
            ->get();

        if ($data->isEmpty()) {
            return [
                'datasets' => [[
                    'data'            => [40, 30, 20, 10],
                    'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b'],
                    'borderWidth'     => 0,
                    'hoverOffset'     => 4,
                ]],
                'labels' => ['Junior','Mid','Senior','Lead'],
            ];
        }

        return [
            'datasets' => [[
                'data'            => $data->pluck('total')->toArray(),
                'backgroundColor' => [
                    '#3b82f6','#8b5cf6','#10b981',
                    '#f59e0b','#f87171','#334155',
                ],
                'borderWidth' => 0,
                'hoverOffset' => 4,
            ]],
            'labels' => $data->pluck('experience_level')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'cutout'  => '68%',
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels'   => [
                        'color'           => '#475569',
                        'usePointStyle'   => true,
                        'pointStyleWidth' => 8,
                        'font'            => ['size' => 10],
                        'padding'         => 10,
                    ],
                ],
                'tooltip' => [
                    'enabled'         => true,
                    'backgroundColor' => '#1e293b',
                    'titleColor'      => '#f1f5f9',
                    'bodyColor'       => '#94a3b8',
                    'borderColor'     => '#334155',
                    'borderWidth'     => 1,
                    'padding'         => 10,
                    'displayColors'   => true,
                ],
            ],
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}