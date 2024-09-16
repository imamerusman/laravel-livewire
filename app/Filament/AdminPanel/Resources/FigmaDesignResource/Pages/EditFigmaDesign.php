<?php

namespace App\Filament\AdminPanel\Resources\FigmaDesignResource\Pages;

use App\Filament\AdminPanel\Resources\FigmaDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFigmaDesign extends EditRecord
{
    protected static string $resource = FigmaDesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
