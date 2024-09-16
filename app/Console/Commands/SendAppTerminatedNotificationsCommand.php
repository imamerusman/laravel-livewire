<?php

namespace App\Console\Commands;

use App\Models\Events\AppTerminationEvent;
use App\Models\Notifications\NotificationTypes;
use App\Notifications\AppTerminationNotification;
use Illuminate\Console\Command;

class SendAppTerminatedNotificationsCommand extends Command
{
    protected $signature = 'send:app-terminated-notifications';

    protected $description = 'Send app terminated notifications to customers.';

    public function handle(): void
    {
        $appTerminationEvents = AppTerminationEvent::query()
            ->withWhereHas('customer.user')
            ->unresolved()
            ->get();

        if ($appTerminationEvents->isEmpty()) {
            printInfo("No notifications to send");
            return;
        }
        $appTerminationEvents->each(function (AppTerminationEvent $event): void {
            $user = $event->customer->user;
            $notification = $user->otherNotifications()
                ->where('type', NotificationTypes::AppTerminationReminder)
                ->enabled()
                ->first();

            if (filled($notification)) {
                printInfo("Sending notification to {$event->customer->name}");
                $delay = now()->addHours($notification->meta_data['after'] ?? 1);
                $event->customer->notify(
                    (new AppTerminationNotification($notification, $event))
                        ->delay($delay)
                );
                $event->markAsPending();
            }
        });
    }
}
