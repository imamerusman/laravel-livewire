<?php

namespace App\Http\Controllers;

use App\Models\Plans\Plan;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;


class PlanSubscriptionController extends Controller
{
    public function subscribe(Plan $plan)
    {
        $user = auth()->user();
         // Todo: check for specific subscription
        if (auth()->check() && !$user->hasCompletedAppPreferencesSteps)
        {
            Notification::make()
                ->title('Nearly there!')
                ->body('You must complete the onboarding steps before you can continue.')
                ->warning()
                ->send();
            return redirect()->route('onboarding');
        }
         if ($user->hasSubscriptions()) {
             if(empty($user->activeSubscription()))
             {
                return $this->handleStripePayment($user, $plan, 'pay');
             }
             if ($user->activeSubscription()->plan->id === $plan->id) {
                 return back()->with('error', 'You are already subscribed to this plan');
             } else {
                 return  $this->handleStripePayment($user, $plan,  'book');
             }
         }
        return $this->handleStripePayment($user, $plan, 'pay');
    }
    /**
     * @param \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null $user
     * @param Plan $plan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleStripePayment(User $user, Plan $plan, $submitType): RedirectResponse
    {
        $stripeSecretKey = config('services.stripe.secret_key');
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        header('Content-Type: application/json', 'Accept: application/json');
        $duration = $plan->duration === 30 ? 'monthly' : 'yearly';
        $url = $user->checkoutCharge(
            amount: $plan->price,
            name: $plan->name . ' Plan'. ' - ' . ucfirst($duration),
            sessionOptions: [
                'payment_method_types' => ['card'],
                'success_url' => route('pricing'),
                'cancel_url' => url('/'),
                'submit_type' => $submitType,
            ]
        );
        return redirect()->to($url->url);
    }
}
