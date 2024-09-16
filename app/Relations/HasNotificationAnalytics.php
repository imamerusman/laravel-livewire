<?php

namespace App\Relations;

use App\Models\NotificationAnalytics;
use App\Models\Notifications\OtherNotification;

trait HasNotificationAnalytics
{
    public function notificationAnalytics() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(NotificationAnalytics::class);
    }
}
