<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopCompanyChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Companies';
    protected static ?string $description = 'Perusahaan dengan jumlah lowongan IT terbanyak';
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '360px';

    protected function getData(): array
    {
        $data = DB::table('jobs_clean')
            ->select('company', DB::raw('COUNT(*) as total'))
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->groupBy('company')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $count  = $data->count();
        $colors = collect(range(0, $count - 1))->map(function ($i) use ($count) {
            $alpha = round(0.85 - ($i / $count) * 0.45, 2);
            return "rgba(59, 130, 246, {$alpha})";
        })->toArray();

        return [
            'datasets' => [
                [
                    'label'           => 'Jumlah Lowongan',
                    'data'            => $data->pluck('total')->toArray(),
                    'backgroundColor' => $colors,
                    'borderWidth'     => 0,
                    'borderRadius'    => 4,
                    'borderSkipped'   => false,
                ],
            ],
            'labels' => $data->pluck('company')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins'   => [
                'legend'  => ['display' => false],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(ctx){ return '  ' + ctx.parsed.x + ' lowongan'; }",
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'grid'        => ['color' => '#0f1724'],
                    'ticks'       => ['color' => '#475569'],
                ],
                'y' => [
                    'grid'  => ['display' => false],
                    'ticks' => ['color' => '#94a3b8', 'font' => ['size' => 12]],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}