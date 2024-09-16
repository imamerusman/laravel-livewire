<?php

namespace App\Filament\AdminPanel\Resources\BlogResource\Pages;

use App\Filament\AdminPanel\Resources\BlogResource;
use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
