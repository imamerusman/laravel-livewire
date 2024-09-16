<?php

namespace App\Filament\AdminPanel\Resources\AppDevOrderResource\Pages;

use App\Filament\AdminPanel\Resources\AppDevOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListAppDevOrders extends ListRecords
{
    protected static string $resource = AppDevOrderResource::class;

    protected function getActions(): array
    {
        return [
//            CreateAction::make(),
        ];
    }
}
