<?php

namespace App\Jobs;

use App\Models\Plans\Plan;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Permission\Models\Role;
use Spatie\WebhookClient\Models\WebhookCall;

class StripePaymentSuccessHandlingEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected WebhookCall $webhookCall)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payLoad = $this->webhookCall->payload['data']['object'];

        if ($payLoad['payment_status'] === 'paid'
            && $payLoad['submit_type'] === 'pay'
            && $payLoad['status'] === 'complete'
        )
        {
            list( $user, $plan) = $this->getDetailsForSubscription();
            $user?->subscribeTo($plan, $plan->duration);
            $user->assignRole(Role::findByName('user'));
            $user->notify(
                Notification::make()
                    ->title('Subscribe '.$user->activeSubscription()->plan->name.' Plan Successfully')
                    ->success()
                    ->icon('heroicon-m-check-circle')
                    ->iconColor('success')
                    ->toDatabase()
            );
        }

        list($user) = $this->getDetailsForSubscription();
        $lastPlanSubscription = $user?->activeSubscription()?->plan?->name;
        if ($payLoad['payment_status'] === 'paid'
            && $payLoad['submit_type'] === 'book'
            && $payLoad['status'] === 'complete'
            && $user->hasActiveSubscription()
        ) {
            list( $user, $plan) = $this->getDetailsForSubscription();
            $expireDate = Carbon::now()->addDays($plan->duration);
            $user->activeSubscription()->update([
                'charging_price' => $plan->price,
                'updated_at' => $plan->created_at,
                'expires_on' => $expireDate,
            ]);

            $user->upgradeCurrentPlanTo($plan, $plan->duration);

            $user->notify(
                Notification::make()
                    ->title(' Upgraded '.$lastPlanSubscription.' Plan to '.$user->activeSubscription()->plan->name.'  Plan Successfully')
                    ->success()
                    ->icon('heroicon-m-check-circle')
                    ->iconColor('success')
                    ->toDatabase()
            );
        }
    }

    /**
     * @return array
     */
    public function getDetailsForSubscription(): array
    {
        $customer = $this->webhookCall->payload['data']['object']['customer'];
        $amount = $this->webhookCall->payload['data']['object']['amount_total'];
        $user = User::where('stripe_id', $customer)->first();
        $plan = Plan::where('price', $amount)->first();
        return array( $user, $plan);
    }
}
