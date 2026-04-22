<?php

namespace App\Filament\Admin\Resources;

use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\JobResource\Pages;
use App\Filament\Admin\Resources\JobResource\RelationManagers;
use App\Models\Job;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('title')->limit(30)->searchable(),
            TextColumn::make('company')->searchable(),
            TextColumn::make('location'),
            TextColumn::make('salary_min')->label('Salary'),
            TextColumn::make('experience_level'),
            TextColumn::make('keyword'),
            TextColumn::make('scraped_at')->dateTime(),
        ])
        ->filters([])
        ->actions([])
        ->bulkActions([]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}
