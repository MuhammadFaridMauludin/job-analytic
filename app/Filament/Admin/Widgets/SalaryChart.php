<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalaryChart extends ChartWidget
{
    protected static ?string $heading = 'Average Salary per Role';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = DB::table('jobs_clean')
            ->select('keyword', DB::raw('AVG(salary_min) as avg_salary'))
            ->whereNotNull('salary_min')
            ->groupBy('keyword')
            ->orderByDesc('avg_salary')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Avg Salary (IDR)',
                    'data' => $data->pluck('avg_salary'),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.85)',
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                ],
            ],
            'labels' => $data->pluck('keyword')->map(fn($name) => \Illuminate\Support\Str::limit($name, 12)),
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
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => ['color' => 'rgba(255,255,255,0.05)'],
                    'ticks' => ['color' => 'rgba(255,255,255,0.5)'],
                ],
                'x' => [
                    'grid' => ['display' => false],
                    'ticks' => [
                        'color' => 'rgba(255,255,255,0.7)',
                        'font' => ['size' => 10],
                        'maxRotation' => 0,
                        'minRotation' => 0,
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}