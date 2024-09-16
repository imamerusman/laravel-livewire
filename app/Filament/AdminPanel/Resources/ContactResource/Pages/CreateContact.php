<?php

namespace App\Filament\AdminPanel\Resources\ContactResource\Pages;

use App\Filament\AdminPanel\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;
}
