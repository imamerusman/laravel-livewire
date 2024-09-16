<?php

namespace App\Filament\AdminPanel\Resources\AppReviewResource\Pages;

use App\Filament\AdminPanel\Resources\AppReviewResource;
use Filament\Resources\Pages\ListRecords;

class ListAppReviews extends ListRecords
{
    protected static string $resource = AppReviewResource::class;

    protected function getActions(): array
    {
        return [
        ];
    }
}
