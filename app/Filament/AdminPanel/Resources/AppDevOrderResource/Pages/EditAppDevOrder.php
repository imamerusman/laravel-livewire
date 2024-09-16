<?php

namespace App\Filament\AdminPanel\Resources\AppDevOrderResource\Pages;

use App\Filament\AdminPanel\Resources\AppDevOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAppDevOrder extends EditRecord
{
    protected static string $resource = AppDevOrderResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
