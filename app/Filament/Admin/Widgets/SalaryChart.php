<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalaryChart extends ChartWidget
{
    protected static ?string $heading = 'Average Salary per Role';
    protected static ?string $description = 'Rata-rata gaji minimum per kategori (juta rupiah)';
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 4;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = DB::table('jobs_clean')
            ->select('keyword', DB::raw('AVG(salary_min) as avg_salary'))
            ->whereNotNull('salary_min')
            ->where('salary_min', '>', 0)
            ->groupBy('keyword')
            ->orderByDesc('avg_salary')
            ->limit(8)
            ->get();

        $salaryInMillions = $data->map(fn ($row) => round($row->avg_salary / 1_000_000, 1));

        return [
            'datasets' => [
                [
                    'label'           => 'Avg Salary (juta Rp)',
                    'data'            => $salaryInMillions->toArray(),
                    'backgroundColor' => 'rgba(99, 102, 241, 0.2)',
                    'borderColor'     => '#6366f1',
                    'borderWidth'     => 2,
                    'pointBackgroundColor' => '#6366f1',
                    'pointBorderColor'     => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor'     => '#6366f1',
                    'pointRadius'     => 4,
                ],
            ],
            'labels' => $data->pluck('keyword')
                ->map(fn ($n) => \Illuminate\Support\Str::limit($n, 15))
                ->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'radar';
    }

    protected function getOptions(): array
    {
        return [
            'interaction' => [
                'mode'      => 'nearest',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => ['display' => false],
                'tooltip' => [
                    'enabled'         => true,
                    'backgroundColor' => '#1e293b',
                    'titleColor'      => '#f1f5f9',
                    'bodyColor'       => '#94a3b8',
                    'borderColor'     => '#334155',
                    'borderWidth'     => 1,
                    'padding'         => 12,
                    'displayColors'   => false,
                ],
            ],
            'scales' => [
                'r' => [
                    'beginAtZero'     => true,
                    'grid'            => ['color' => 'rgba(255,255,255,0.06)'],
                    'angleLines'      => ['color' => 'rgba(255,255,255,0.06)'],
                    'pointLabels'     => [
                        'color' => '#94a3b8',
                        'font'  => ['size' => 10],
                    ],
                    'ticks' => [
                        'color'           => '#475569',
                        'backdropColor'   => 'transparent',
                        'font'            => ['size' => 9],
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}