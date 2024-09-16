<?php

namespace App\Console\Commands;

use App\Models\Events\CartAbandonmentEvent;
use App\Models\Notifications\NotificationTypes;
use App\Notifications\AbandonedNotification;
use Illuminate\Console\Command;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;

class SendCartNotificationsCommand extends Command
{
    protected $signature = 'send:cart-notifications';

    protected $description = 'Send notifications to customers who have items
     in their cart but have not checked out yet.';

    /**
     * @throws MessagingException
     * @throws FirebaseException
     */
    public function handle(): void
    {
        $cartAbandonmentEvents = CartAbandonmentEvent::query()
            ->withWhereHas('customer.user')
            ->unresolved()
            ->get();

        if ($cartAbandonmentEvents->isEmpty()) {
            printInfo("No notifications to send");
            return;
        }
        $cartAbandonmentEvents->each(function (CartAbandonmentEvent $event): void {
            $user = $event->customer->user;
            $notification = $user->otherNotifications()
                ->where('type', NotificationTypes::AbandonedCartReminder)
                ->enabled()
                ->first();
            if (filled($notification)) {
                printInfo("Sending notification to {$event->customer->name}");
                $delay = now()->addHours($notification->meta_data['after'] ?? 1);
                $event->customer->notify(
                    (new AbandonedNotification($notification, $event))
                ->delay($delay)
                );
            }
        });
    }
}
