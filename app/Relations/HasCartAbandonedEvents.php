<?php

namespace App\Relations;

use App\Models\Events\CartAbandonmentEvent;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasCartAbandonedEvents
{
    public function cartAbandonmentEvents(): HasMany
    {
        return $this->hasMany(CartAbandonmentEvent::class);
    }
}
