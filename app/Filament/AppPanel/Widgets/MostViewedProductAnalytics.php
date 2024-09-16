<?php

namespace App\Filament\AppPanel\Widgets;

use App\Models\ProductAnalytics;
use Filament\Widgets\ChartWidget;

class MostViewedProductAnalytics extends ChartWidget
{
    protected static ?string $heading = 'Most Viewed Product Analytics';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {

        $viewProducts = ProductAnalytics::query()
            ->select('name')
            ->selectRaw('SUM(views) as views')
            ->groupBy('name')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        $totalViews = $viewProducts->groupBy('name')
            ->map(function ($productAnalyticsCollection) {
                return $productAnalyticsCollection->sum('views');
            });
        return [
            'datasets' => [
                [
                    'label' => 'Most Viewed Product',
                    'data' => $totalViews->values()->toArray(),
                ],
            ],
                    'labels' => $totalViews->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
