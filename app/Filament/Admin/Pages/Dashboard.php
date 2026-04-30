<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\StatsOverview;
use App\Filament\Admin\Widgets\TrendChart;
use App\Filament\Admin\Widgets\CategoryDonutChart;
use App\Filament\Admin\Widgets\TopCompanyChart;
use App\Filament\Admin\Widgets\SalaryChart;
use App\Filament\Admin\Widgets\ExperienceChart;
use App\Filament\Admin\Widgets\TopSkillsChart;
use App\Filament\Admin\Widgets\TopCityChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard Analitik';
    protected static ?string $navigationGroup = 'Analisis';
    protected static ?int $navigationSort = 1;

    public function getColumns(): int | array
    {
        return 12;
    }

    public function getWidgets(): array
    {
        return [
            // Row 1
            StatsOverview::class,

            // Row 2
            TrendChart::class, //8
            CategoryDonutChart::class, //4

            // Row 3
            TopSkillsChart::class,
            TopCompanyChart::class,

            // Row 4
            SalaryChart::class, //6
            ExperienceChart::class, //6
            
            TopCityChart::class,
        ];
    }
}