<?php

namespace App\Filament\AdminPanel\Resources\AppDevOrderResource\Pages;

use App\Filament\AdminPanel\Resources\AppDevOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAppDevOrder extends CreateRecord
{
    protected static string $resource = AppDevOrderResource::class;

    protected function getActions(): array
    {
        return [

        ];
    }
}
