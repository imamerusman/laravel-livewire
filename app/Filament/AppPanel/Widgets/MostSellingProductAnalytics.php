<?php

namespace App\Filament\AppPanel\Widgets;

use App\Models\ProductAnalytics;
use Filament\Widgets\ChartWidget;

class MostSellingProductAnalytics extends ChartWidget
{
    protected static ?string $heading = 'Most Selling Product Analytics';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        $saleProducts = ProductAnalytics::query()
            ->select('name')
            ->selectRaw('SUM(sales) as sales')
            ->groupBy('name')
            ->orderByDesc('sales')
            ->limit(10)
            ->get();
        $totalSale = $saleProducts->mapToGroups(function ($item) {
            return [$item->name => $item->sales];
        })->map(function ($sales) {
            return $sales->sum();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Most Selling Product',
                    'data' => $totalSale->values()->toArray(),
                ],
            ],
            'labels' => $totalSale->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
