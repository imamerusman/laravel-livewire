<?php

namespace App\Filament\AppPanel\Resources\LiveStreamResource\Pages;

use App\Filament\AppPanel\Resources\LiveStreamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLiveStream extends EditRecord
{
    protected static string $resource = LiveStreamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
