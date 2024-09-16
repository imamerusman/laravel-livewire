<?php

namespace App\Filament\AppPanel\Resources\NotificationResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class SalesByEachNotificationCategory extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()->hasRole('user');
    }

    protected function getCards(): array
    {
        return [
            Card::make('Cart Type Notifications', '$192.1k')
                ->description('32k increase')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Card::make('Recent Product Notification', '1340')
                ->description('3% decrease')
                ->descriptionIcon('heroicon-s-trending-down')
                ->chart([17, 16, 14, 15, 14, 13, 12])
                ->color('danger'),
            Card::make('Shopping Time Alerts', '3543')
                ->description('7% increase')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
            Card::make('Reminder After App Termination', '3543')
                ->description('7% increase')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
        ];
    }
}
