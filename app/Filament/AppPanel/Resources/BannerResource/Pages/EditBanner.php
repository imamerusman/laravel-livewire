<?php

namespace App\Filament\AppPanel\Resources\BannerResource\Pages;

use App\Filament\AppPanel\Resources\BannerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBanner extends EditRecord
{
    protected static string $resource = BannerResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
