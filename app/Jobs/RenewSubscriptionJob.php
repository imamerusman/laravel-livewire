<?php

namespace App\Jobs;

use App\Models\Plans\PlanSubscription;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Exceptions\IncompletePayment;

class RenewSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly User $user)
    {
    }

    public function handle(): void
    {
        /** @var PlanSubscription $lastSubscription */
        $lastSubscription = $this->user->lastSubscription()->first();
        if (filled($lastSubscription)) {

            if (filled($this->user->defaultPaymentMethod())) {
                try {
                    $this->user->charge($lastSubscription->plan->price, $this->user->defaultPaymentMethod()->id);

                    $this->user->extendCurrentSubscriptionUntil(now()->addDays($lastSubscription->plan->duration));

                    Notification::make()
                        ->title('Subscription Renewed')
                        ->body('Your Subscription has been renewed.')
                        ->sendToDatabase($this->user);

                } catch (IncompletePayment $e) {
                    Log::error($e->getMessage(), ['user' => $this->user->toArray()]);
                    printInfo('Incomplete Payment for user ' . $this->user->id);
                    // TODO: Send email to user to complete payment
                    $this->user->createInvoice([
                        'collection_method' => 'send_invoice',
                        'description' => 'This is Reminder to pay your invoice for  ' . $lastSubscription->plan->name . ' plan.',
                        'due_date' => now()->addDays(5)->timestamp,
                    ]);
                }
            }
        }
    }
}
