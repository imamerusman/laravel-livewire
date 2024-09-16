<?php

namespace App\Relations;

use App\Models\Notifications\OtherNotification;

trait HasOtherNotifications
{
    public function otherNotifications()
    {
        return $this->hasMany(OtherNotification::class);
    }
}
