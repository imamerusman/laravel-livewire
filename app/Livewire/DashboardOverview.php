<?php

namespace App\Livewire;

use Carbon\Carbon;
use Filament\Notifications\Notification;
use Livewire\Component;

class DashboardOverview extends Component
{
    protected static string $view = 'livewire.dashboard-overview';

    public static function canView(): bool
    {
        return auth()->user()->hasSubscriptions();
    }

    public static function getSort(): string
    {
        return auth()->user()->id;
    }

    public function cancelSubscription(): void
    {
        $lastSubscription = Carbon::parse(auth()->user()->lastSubscription()->expires_on)->toDate();
        $expireDate = $lastSubscription->format('Y-m-d h:i:s');
        //cancel subscription
        $user = auth()->user();
        $user->cancelCurrentSubscription();
        $user->save();
        $user->lastSubscription()->update([
            'cancelled_on' => $expireDate,
        ]);
        $user->notify(
            Notification::make()
                ->title('Subscription Cancelled')
                ->body('Your subscription has been cancelled')
                ->info()
                ->icon('heroicon-s-information-circle')
                ->iconColor('warning')
                ->toDatabase()
        );
        $this->redirect('/pricing');
    }
}
