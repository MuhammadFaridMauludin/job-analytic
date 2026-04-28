<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ExperienceChart extends ChartWidget
{
    protected static ?string $heading = 'Experience Level Distribution';
    protected static ?string $description = 'Proporsi lowongan berdasarkan level pengalaman';
    protected static ?int $sort = 4;

    // Berdampingan dengan SalaryChart
    protected int | string | array $columnSpan = 6;
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

        return [
            'datasets' => [
                [
                    'data'            => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6',
                        '#8b5cf6',
                        '#10b981',
                        '#f59e0b',
                        '#f87171',
                        '#334155',
                    ],
                    'borderWidth' => 0,
                    'hoverOffset' => 6,
                ],
            ],
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
                        'padding'         => 14,
                        'usePointStyle'   => true,
                        'pointStyleWidth' => 8,
                        'color'           => '#64748b',
                        'font'            => ['size' => 11],
                    ],
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(ctx){
                            var total = ctx.dataset.data.reduce(function(a,b){return a+b;},0);
                            var pct = ((ctx.parsed / total)*100).toFixed(1);
                            return '  ' + ctx.label + ': ' + ctx.formattedValue + ' (' + pct + '%)';
                        }",
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}