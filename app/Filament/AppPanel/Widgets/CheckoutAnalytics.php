<?php

namespace App\Filament\AppPanel\Widgets;

use Filament\Widgets\ChartWidget;

class CheckoutAnalytics extends ChartWidget
{
    protected static ?string $heading = 'Checkout Analytics';
    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Checkout Analytics',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
