<?php

namespace App\Filament\AdminPanel\Resources\FeatureResource\Pages;

use App\Filament\AdminPanel\Resources\FeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListFeatures extends ListRecords
{
    protected static string $resource = FeatureResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

  /*  protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->groupBy('name');
    }*/
}
