<?php

namespace App\Filament\AppPanel\Widgets;

use App\Models\Events\CartAbandonmentEvent;
use Filament\Forms\Components\Card;
use Filament\Infolists\Components\Section;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CartStatesOverview extends BaseWidget
{
    protected static ?string $heading = 'Cart Analytics';
    protected static ?string $pollingInterval = null;
    protected function getStats(): array
    {
        $totalCartAbondend = CartAbandonmentEvent::count('status');
        $resolvedStatus = CartAbandonmentEvent::whereStatus('resolved')->count('status');
        $unresolvedStatus = CartAbandonmentEvent::whereStatus('unresolved')->count('status');
        return [
            Stat::make('Total Cart',  $totalCartAbondend)
                ->color('success'),
            Stat::make('Total Resolved', $resolvedStatus)
                ->color('success'),
            Stat::make('Total Unresolved', $unresolvedStatus)
                ->color(Color::hex('#262626')),

        ];
    }
}
