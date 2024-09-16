<?php

namespace App\Filament\AppPanel\Resources\CustomerResource\Pages;

use App\Filament\AppPanel\Resources\CustomerResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getActions(): array
    {
        return [];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('user_id', auth()->id());
    }
}
