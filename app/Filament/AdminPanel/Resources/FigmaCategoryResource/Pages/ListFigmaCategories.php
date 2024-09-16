<?php

namespace App\Filament\AdminPanel\Resources\FigmaCategoryResource\Pages;

use App\Filament\AdminPanel\Resources\FigmaCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFigmaCategories extends ListRecords
{
    protected static string $resource = FigmaCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
