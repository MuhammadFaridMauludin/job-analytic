<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class SkillsTeknologi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Analisis';
    protected static ?string $navigationLabel = 'Skills Teknologi';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.admin.pages.skills-teknologi';
}