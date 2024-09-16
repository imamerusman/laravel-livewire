<?php

namespace App\Filament\AdminPanel\Resources\BlogResource\Pages;

use App\Filament\AdminPanel\Resources\BlogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;
}
