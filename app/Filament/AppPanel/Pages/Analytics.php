<?php

namespace App\Filament\AppPanel\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class Analytics extends Page

{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static string $view = 'filament.app-panel.pages.analytics';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Notification';
}
