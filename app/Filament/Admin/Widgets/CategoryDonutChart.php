<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CategoryDonutChart extends ChartWidget
{
    protected static ?string $heading = 'Kategori Bidang IT';
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 4;
    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
    $data = DB::table('jobs_clean')
    ->select('keyword', DB::raw('COUNT(*) as total')) 
    ->whereNotNull('keyword')
    ->where('keyword', '!=', '')
    ->groupBy('keyword')
    ->orderByDesc('total')
    ->limit(6)
    ->get();

        // Fallback
        if ($data->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'data'            => [32, 24, 18, 9, 7, 10],
                        'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#f87171','#334155'],
                        'borderWidth'     => 0,
                        'hoverOffset'     => 4,
                    ],
                ],
                'labels' => ['Software Dev','Data/AI','DevOps/Cloud','Security','QA','Lainnya'],
            ];
        }

        return [
            'datasets' => [
                [
                    'data'            => $data->pluck('total')->toArray(),
                    'backgroundColor' => ['#3b82f6','#8b5cf6','#10b981','#f59e0b','#f87171','#334155'],
                    'borderWidth'     => 0,
                    'hoverOffset'     => 4,
                ],
            ],
            'labels' => $data->pluck('keyword')->toArray(),  // ← ganti category → keyword
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
            ],
            'maintainAspectRatio' => false,
        ];
    }
}