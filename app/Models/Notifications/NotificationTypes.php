<?php

namespace App\Models\Notifications;

enum NotificationTypes: string
{
    case AbandonedCartReminder = 'abandoned_cart';
    case RecentProductReminder = 'recent_product';
    case ShoppingTimeReminder = 'shopping_time';
    case AppTerminationReminder = 'app_termination';
}
