<?php

namespace App\Filament\AppPanel\Resources\BannerResource\Pages;

use App\Filament\AppPanel\Resources\BannerResource;
use Exception;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

    /**
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('user_id', auth()->id());
    }
}
