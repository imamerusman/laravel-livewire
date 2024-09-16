<?php

namespace App\Filament\AdminPanel\Resources\BlogResource\Pages;

use App\Filament\AdminPanel\Resources\BlogResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlogs extends ListRecords
{
    protected static string $resource = BlogResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
