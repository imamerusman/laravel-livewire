<?php

namespace App\Filament\AdminPanel\Resources\PlanResource\Pages;

use App\Filament\AdminPanel\Resources\PlanResource;
use App\Models\Plans\Plan;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlans extends ListRecords
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
