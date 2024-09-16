<?php

namespace App\Relations;

use App\Models\Events\AppTerminationEvent;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasAppTerminationEvents
{
    public function appTerminationEvents(): HasMany
    {
        return $this->hasMany(AppTerminationEvent::class);
    }
}
