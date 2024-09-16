<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;

class NotificationSentListener
{
    public function __construct()
    {
    }

    public function handle(NotificationSent $event): void
    {
        if (method_exists($event->notification, 'sent')) {
            $event->notification->sent();
        } else {
            Log::warning('NotificationSentListener: NotificationSent event fired but no sent method found');
        }
    }
}
