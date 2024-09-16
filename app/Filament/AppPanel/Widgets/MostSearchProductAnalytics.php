<?php

namespace App\Filament\AppPanel\Widgets;

use App\Models\ProductAnalytics;
use Filament\Widgets\ChartWidget;

class MostSearchProductAnalytics extends ChartWidget
{
    protected static ?string $heading = 'Most Search Product Analytics';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        $searchedProducts = ProductAnalytics::query()
            ->select('name')
            ->selectRaw('SUM(searches) as searches')
            ->groupBy('name')
            ->orderByDesc('searches')
            ->limit(10)
            ->get()
            ->groupBy('name')
            ->map(function ($productAnalyticsCollection) {
                return $productAnalyticsCollection->sum('searches');
            });
        $totalSearch = $searchedProducts;
        return [
            'datasets' => [
                [
                    'label' => 'Most Search Product',
                    'data' => $totalSearch->values()->toArray(),
                ],
            ],
            'labels' => $totalSearch->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
