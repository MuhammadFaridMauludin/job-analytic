<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TrendChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Lowongan IT per Bulan';
    protected static ?string $description = '2025–2026';
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 8;
    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $data = DB::table('jobs_clean')
            ->selectRaw('YEAR(scraped_at) as year, MONTH(scraped_at) as month_num, DATE_FORMAT(MIN(scraped_at), "%b") as month, COUNT(*) as total')
            ->whereNotNull('scraped_at')
            ->groupByRaw('YEAR(scraped_at), MONTH(scraped_at)')
            ->orderByRaw('YEAR(scraped_at), MONTH(scraped_at)')
            ->limit(11)
            ->get();

        // Fallback
        if ($data->isEmpty()) {
            $months = ['Jun','Jul','Agu','Sep','Okt','Nov','Des','Jan','Feb','Mar','Apr'];
            $raw    = [28400,29100,30200,31800,33500,35200,37800,40100,42600,45300,48320];
            $avg    = array_map(function($i) use ($raw) {
                return $i < 2 ? null : round(($raw[$i] + $raw[$i-1] + $raw[$i-2]) / 3);
            }, array_keys($raw));

            return [
                'datasets' => [
                    [
                        'label'           => 'Lowongan Baru',
                        'data'            => $raw,
                        'borderColor'     => '#3b82f6',
                        'backgroundColor' => 'rgba(59,130,246,0.07)',
                        'borderWidth'     => 2,
                        'fill'            => true,
                        'tension'         => 0.4,
                        'pointRadius'     => 3,
                        'pointBackgroundColor' => '#3b82f6',
                    ],
                    [
                        'label'       => 'Rata-rata 30 hari',
                        'data'        => $avg,
                        'borderColor' => '#8b5cf6',
                        'borderWidth' => 1.5,
                        'borderDash'  => [5, 4],
                        'fill'        => false,
                        'tension'     => 0.4,
                        'pointRadius' => 0,
                    ],
                ],
                'labels' => $months,
            ];
        }

        $raw = $data->pluck('total')->toArray();
        $avg = array_map(function($i) use ($raw) {
            return $i < 2 ? null : round(($raw[$i] + $raw[$i-1] + $raw[$i-2]) / 3);
        }, array_keys($raw));

        return [
            'datasets' => [
                [
                    'label'           => 'Lowongan Baru',
                    'data'            => $raw,
                    'borderColor'     => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.07)',
                    'borderWidth'     => 2,
                    'fill'            => true,
                    'tension'         => 0.4,
                    'pointRadius'     => 3,
                    'pointBackgroundColor' => '#3b82f6',
                ],
                [
                    'label'       => 'Rata-rata 30 hari',
                    'data'        => $avg,
                    'borderColor' => '#8b5cf6',
                    'borderWidth' => 1.5,
                    'borderDash'  => [5, 4],
                    'fill'        => false,
                    'tension'     => 0.4,
                    'pointRadius' => 0,
                ],
            ],
            'labels' => $data->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display'  => true,
                    'position' => 'bottom',
                    'labels'   => [
                        'color'           => '#475569',
                        'usePointStyle'   => true,
                        'pointStyleWidth' => 8,
                        'font'            => ['size' => 11],
                        'padding'         => 16,
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'grid'  => ['color' => '#0f1724'],
                    'ticks' => ['color' => '#475569', 'font' => ['size' => 11]],
                ],
                'y' => [
                    'grid'  => ['color' => '#0f1724'],
                    'ticks' => [
                        'color'    => '#475569',
                        'font'     => ['size' => 11],
                        'callback' => "function(v){ return (v/1000).toFixed(0)+'K'; }",
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}