<?php

namespace App\Filament\AdminPanel\Resources\BlogResource\Pages;

use App\Filament\AdminPanel\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBlog extends ViewRecord
{
    protected static string $resource = BlogResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
