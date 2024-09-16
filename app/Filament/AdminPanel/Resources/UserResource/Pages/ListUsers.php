<?php

namespace App\Filament\AdminPanel\Resources\UserResource\Pages;

use App\Filament\AdminPanel\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
}
