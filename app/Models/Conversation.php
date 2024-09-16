<?php

namespace App\Models;

use App\Relations\Conversation\HasReceiver;
use App\Relations\Conversation\HasSender;
use App\Relations\HasMessages;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasMessages, HasSender, HasReceiver;

    protected $casts = [
        'last_message_at' => 'datetime',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected function recipientUser(): Attribute
    {
        $this->loadMissing('sender', 'receiver');
        return Attribute::make(
            get: function () {
                if ($this->receiver->id === auth()->id()) {
                    return $this->sender;
                } else {
                    return $this->receiver;
                }
            },
        );
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', true);
    }

}
