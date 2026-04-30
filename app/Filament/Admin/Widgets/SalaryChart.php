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
            ->limit(10)
            ->get();

        $salaryInMillions = $data->map(fn ($row) => round($row->avg_salary / 1_000_000, 1));

        $colors = [
            '#3b82f6','#6366f1','#8b5cf6','#a855f7','#ec4899',
            '#f43f5e','#f97316','#f59e0b','#10b981','#06b6d4',
        ];

        return [
            'datasets' => [
                [
                    'label'                => 'Avg Salary (juta Rp)',
                    'data'                 => $salaryInMillions->toArray(),
                    'backgroundColor'      => array_slice($colors, 0, $data->count()),
                    'borderWidth'          => 0,
                    'borderRadius'         => 8,
                    'borderSkipped'        => false,
                    'barPercentage'        => 0.75,
                    'categoryPercentage'   => 0.85,
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
            'indexAxis'   => 'y',   // ✅ horizontal seperti TopSkillsChart
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
                        'precision' => 1,
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