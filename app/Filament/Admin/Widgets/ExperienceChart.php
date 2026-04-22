<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ExperienceChart extends ChartWidget
{
    protected static ?string $heading = 'Experience Level Distribution';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = DB::table('jobs_clean')
            ->select('experience_level', DB::raw('COUNT(*) as total'))
            ->whereNotNull('experience_level')
            ->groupBy('experience_level')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('total'),
                    'backgroundColor' => [
                        'rgba(99, 102, 241, 0.85)',
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(239, 68, 68, 0.85)',
                        'rgba(139, 92, 246, 0.85)',
                    ],
                    'borderWidth' => 2,
                    'hoverOffset' => 8,
                ],
            ],
            'labels' => $data->pluck('experience_level'),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'color' => 'rgba(255,255,255,0.7)',
                        'padding' => 16,
                        'font' => ['size' => 12],
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}