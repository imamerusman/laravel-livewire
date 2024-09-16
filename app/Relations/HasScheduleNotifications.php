<?php

namespace App\Relations;

use App\Models\Notifications\ScheduleNotification;

trait HasScheduleNotifications
{
    public function scheduleNotifications()
    {
        return $this->hasMany(ScheduleNotification::class);
    }
}
