<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopSkillsChart extends ChartWidget
{
    protected static ?string $heading = 'Top Skill IT';
    protected static ?string $description = 'Perusahaan dengan jumlah lowongan IT terbanyak';
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

                if (!isset($skillCount[$skill])) {
                    $skillCount[$skill] = 0;
                }

                $skillCount[$skill]++;
            }
        }

        // 🔥 urutkan & ambil top 10
        arsort($skillCount);
        $topSkills = array_slice($skillCount, 0, 10, true);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Lowongan',
                    'data' => array_values($topSkills),
                ],
            ],
            'labels' => array_keys($topSkills),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // bisa juga 'pie'
    }
}