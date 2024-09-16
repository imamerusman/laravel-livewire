<?php

namespace App\Filament\AppPanel\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class NotificationAnalytics extends ChartWidget
{
    protected static ?string $heading = 'Notification Analytics';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        $statuses = ['send', 'pending','failed'];

        $monthsData = \App\Models\NotificationAnalytics::whereIn('status', $statuses)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('M');
            })
            ->map(function ($item) {
                /*return $item->count();*/
                return [
                    'send' => $item->where('status', 'send')->count(),
                    'pending' => $item->where('status', 'pending')->count(),
                    'failed' => $item->where('status', 'failed')->count(),
                ];
            });
        return [
            'datasets' => [
                [
                    'label' => 'Pending Notification',
                    'data' => $monthsData->values()->pluck('pending')->toArray(),
                    'borderColor' => '#f97316',
                    'backgroundColor' => '#f97316',
                ],
                [
                    'label' => 'Send Notification',
                    'data' => $monthsData->values()->pluck('send')->toArray(),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => '#22c55e',
                ],
                [
                    'label' => 'Failed Notification',
                    'data' => $monthsData->values()->pluck('failed')->toArray(),
                    'borderColor' => '#dc2626',
                    'backgroundColor' => '#dc2626',
                ],
            ],
            'labels' => $monthsData->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
