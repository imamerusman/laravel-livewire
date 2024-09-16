<?php

namespace App\Filament\AppPanel\Resources\BannerResource\Pages;

use App\Filament\AppPanel\Resources\BannerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBanner extends CreateRecord
{
    protected static string $resource = BannerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['type'] = 'banner';

        return $data;
    }
}
