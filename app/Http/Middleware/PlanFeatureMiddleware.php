<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class PlanFeatureMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && app()->isLocal()) {
            return $next($request);
        }
        if (!auth()->check()) {
            Notification::make()
                ->title('Nearly there!')
                ->body('You must login before you can continue.')
                ->warning()
                ->send();
            return redirect()->to('app/login');
        }
        $user = auth()->user();
        if (auth()->user()->is_admin) {
            return $next($request);
        }
        if (!$user->hasCompletedAppPreferencesSteps) {
            Notification::make()
                ->title('Nearly there!')
                ->body('You must complete the onboarding steps before you can continue.')
                ->warning()
                ->send();
            return redirect()->route('onboarding');
        }

        if($user->getPlanSubscriptions()->paid()->expired()->latest('starts_on')->exists()){

            notify()->info('Your subscription has expired. Please subscribe a plan.',
                'Warning',
            );
            return redirect()->to('/pricing');
        }

        if (!$user->has_plan && $user->hasSubscriptions()) {
            $cancelSubscription = Carbon::parse(auth()?->user()?->lastSubscription()->cancelled_on);
            $currentDate = Carbon::now();
            if ($cancelSubscription > $currentDate) {
                return $next($request);
            }
            Notification::make()
                ->title('Nearly there!')
                ->body('You must subscribe a plan before you can continue.')
                ->warning()
                ->send();

            return redirect()->to('/pricing');
        }

        if ($user->has_plan) {
            return $next($request);
        }
        Notification::make()
            ->title('Nearly there!')
            ->body('You must subscribe a plan before you can continue.')
            ->warning()
            ->send();
        return redirect()->to('/pricing');
    }
}
