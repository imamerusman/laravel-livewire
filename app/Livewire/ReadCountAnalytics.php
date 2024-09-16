<?php

namespace App\Livewire;

use App\Models\Notifications\OtherNotification;
use App\Models\User;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReadCountAnalytics extends BaseWidget
{
    public OtherNotification|null $model = null;
    protected static ?string $heading = 'Cart Analytics';
    protected static ?string $pollingInterval = null;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Rate',  $this->model?->open_count + $this->model?->sale_count)
                ->color('success'),
            Stat::make('Click Rate', $this->model?->open_count)
                ->color('success'),
            Stat::make('Sale Rale', $this->model?->sale_count)
                ->color(Color::hex('#262626')),

        ];
    }
}
