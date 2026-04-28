<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class TrendLowongan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationGroup = 'Analisis';
    protected static ?string $navigationLabel = 'Tren Lowongan';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.admin.pages.trend-lowongan';
}