<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class PetaSebaran extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Analisis';
    protected static ?string $navigationLabel = 'Peta Sebaran';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.admin.pages.peta-sebaran';
}