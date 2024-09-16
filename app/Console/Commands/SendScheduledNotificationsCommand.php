<?php

namespace App\Console\Commands;

use App\Models\Notifications\ScheduleNotification;
use App\Notifications\ScheduleNotification as ScheduleNotificationJob;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Console\Command;

class SendScheduledNotificationsCommand extends Command
{
    protected $signature = 'send:scheduled-notifications';

    protected $description = 'Send scheduled notifications to customers.';

    public function handle(): void
    {
        printInfo("Sending ..");

        $notifications = ScheduleNotification::query()
            ->withWhereHas('user.customers')
            ->upcoming()
            ->pending()
            ->orderBy('scheduled_at')
            ->get();

        if ($notifications->isEmpty()) {
            printInfo("No notifications to send.");
        } else {
            foreach ($notifications as $notification) {
                $customers = $notification->user->customers;

                foreach ($customers as $customer) {
                    try {
                        $timezone = new DateTimeZone($customer->timezone);
                    } catch (Exception $e) {
                        unset($e);
                        printInfo(
                            "Skipping notification for Customer: {$customer->email}
                         because of invalid timezone: $customer->timezone"
                        );
                        continue;
                    }

                    // Convert the scheduled time to the customer's timezone
                    $scheduledTime = Carbon::parse(
                        $notification->scheduled_at,
                        $timezone
                    );

                    // Allow a 1-minute grace period
                    if ($scheduledTime->isPast() || $scheduledTime->subSeconds(60)->isPast()) {
                        // The scheduled time has passed, so we can send the notification to the customer
                        printInfo("Sending notification to customer: $customer->email ...");
                        $customer->notify(new ScheduleNotificationJob($notification));
                    }
                }
            }

            printInfo("Done. All notifications processed.");
        }

        $missedNotifications = ScheduleNotification::query()
            ->withWhereHas('user.customers')
            ->past()
            ->pending()
            ->orderBy('scheduled_at')
            ->get();

        if ($missedNotifications->isNotEmpty()) {
            foreach ($missedNotifications as $notification) {
                $notification->markAs(ScheduleNotification::MISSED);
            }
        }
    }
}
