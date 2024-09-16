<?php

namespace App\Filament\AdminPanel\Resources\SplashScreenResource\Pages;

use App\Filament\AdminPanel\Resources\SplashScreenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSplashScreens extends ListRecords
{
    protected static string $resource = SplashScreenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
