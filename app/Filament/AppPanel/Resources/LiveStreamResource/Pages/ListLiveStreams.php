<?php

namespace App\Filament\AppPanel\Resources\LiveStreamResource\Pages;

use App\Filament\AppPanel\Resources\LiveStreamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLiveStreams extends ListRecords
{
    protected static string $resource = LiveStreamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
