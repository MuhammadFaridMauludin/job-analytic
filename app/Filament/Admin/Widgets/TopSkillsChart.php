<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopSkillsChart extends ChartWidget
{
    protected static ?string $heading = 'Top Skill IT';
    protected static ?string $description = 'Skill yang paling banyak dicari perusahaan';
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = '4';
    protected static ?string $maxHeight = '360px';

    protected function getData(): array
    {
        $rows = DB::table('jobs_clean')
            ->whereNotNull('skills')
            ->pluck('skills');

        $skillCount = [];

        foreach ($rows as $row) {
            $skills = explode(',', strtolower($row));
            foreach ($skills as $skill) {
                $skill = trim($skill);
                if ($skill == '') continue;
                $skillCount[$skill] = ($skillCount[$skill] ?? 0) + 1;
            }
        }

        arsort($skillCount);
        $topSkills = array_slice($skillCount, 0, 10, true);

        $colors = [
            '#3b82f6','#8b5cf6','#10b981','#f59e0b','#f87171',
            '#06b6d4','#ec4899','#84cc16','#f97316','#334155',
        ];

        return [
            'datasets' => [
                [
                    'label'           => 'Jumlah Lowongan',
                    'data'            => array_values($topSkills),
                    'backgroundColor' => $colors,
                    'borderRadius'    => 6,
                    'borderWidth'     => 0,
                    'hoverOffset'     => 4,
                ],
            ],
            'labels' => array_keys($topSkills),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
{
    return [
        'indexAxis' => 'x',
        'interaction' => [
            'mode'      => 'index',
            'intersect' => false,
        ],
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
            'tooltip' => [
                'enabled'         => true,
                'backgroundColor' => '#1e293b',
                'titleColor'      => '#e2e8f0',
                'bodyColor'       => '#94a3b8',
                'borderColor'     => '#334155',
                'borderWidth'     => 1,
                'padding'         => 10,
                'displayColors'   => true,
            ],
        ],
        'scales' => [
            'x' => [
                'display' => true,
                'grid'    => [
                    'color'   => 'rgba(255,255,255,0.05)',
                    'display' => true,
                ],
                'ticks' => [
                    'color' => '#94a3b8',
                    'font'  => ['size' => 11],
                ],
            ],
            'y' => [
                'display' => true,
                'grid'    => ['display' => false],
                'ticks'   => [
                    'color' => '#e2e8f0',
                    'font'  => ['size' => 12],
                ],
            ],
        ],
        'maintainAspectRatio' => false,
    ];
}
}