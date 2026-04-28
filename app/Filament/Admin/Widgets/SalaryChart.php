<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalaryChart extends ChartWidget
{
    protected static ?string $heading = 'Average Salary per Role';
    protected static ?string $description = 'Rata-rata gaji minimum per kategori (juta rupiah)';
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 6;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = DB::table('jobs_clean')
            ->select('keyword', DB::raw('AVG(salary_min) as avg_salary'))
            ->whereNotNull('salary_min')
            ->where('salary_min', '>', 0)
            ->groupBy('keyword')
            ->orderByDesc('avg_salary')
            ->limit(10)
            ->get();

        $salaryInMillions = $data->map(fn ($row) => round($row->avg_salary / 1_000_000, 1));

        return [
            'datasets' => [
                [
                    'label'           => 'Avg Salary (juta Rp)',
                    'data'            => $salaryInMillions->toArray(),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'borderColor'     => 'rgba(16, 185, 129, 1)',
                    'borderWidth'     => 2,
                    'borderRadius'    => 6,
                    'borderSkipped'   => false,
                ],
            ],
            'labels' => $data->pluck('keyword')
                ->map(fn ($n) => \Illuminate\Support\Str::limit($n, 13))
                ->toArray(),
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
                'legend'  => ['display' => false],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(ctx){ return '  Rp ' + ctx.parsed.y.toFixed(1) + ' juta'; }",
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid'        => ['color' => '#0f1724'],
                    'ticks'       => [
                        'color'    => '#475569',
                        'callback' => "function(v){ return 'Rp' + v + 'M'; }",
                    ],
                ],
                'x' => [
                    'grid'  => ['display' => false],
                    'ticks' => [
                        'color'       => '#64748b',
                        'font'        => ['size' => 10],
                        'maxRotation' => 30,
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}