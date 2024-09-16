<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Builder;

trait StatusResolver
{
    public function scopeUnresolved(Builder $query): Builder
    {
        return $query->where('status', EventStatusTypes::UNRESOLVED);
    }

    public function scopeResolved(Builder $query): Builder
    {
        return $query->where('status', EventStatusTypes::RESOLVED);
    }

    public function markAsResolved(): void
    {
        $this->update(['status' => EventStatusTypes::RESOLVED]);
    }

    public function markAsPending(): void
    {
        $this->update(['status' => EventStatusTypes::PENDING]);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => EventStatusTypes::FAILED]);
    }
}
