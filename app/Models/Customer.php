<?php

namespace App\Models;

use App\Interfaces\HasMessages;
use App\Relations\BelongsToUser;
use App\Relations\HasAppTerminationEvents;
use App\Relations\HasCartAbandonedEvents;
use App\Relations\HasNotificationAnalytics;
use App\Relations\HasWishList;
use App\Traits\Messagable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customer extends Model implements HasMessages
{
    use Notifiable,
        BelongsToUser,
        HasWishList,
        HasCartAbandonedEvents,
        HasAppTerminationEvents,
        HasNotificationAnalytics,
        HasFactory,
        Messagable;


    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => filled($value) ? $value : 'Guest',
        );
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => filled($value) ? $value : '-',
        );
    }
}
