<?php

namespace App\Filament\AdminPanel\Resources\SplashScreenResource\Pages;

use App\Filament\AdminPanel\Resources\SplashScreenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSplashScreen extends EditRecord
{
    protected static string $resource = SplashScreenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
