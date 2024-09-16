<?php


namespace App\Relations;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasConversations
{
    public function conversations(): MorphMany
    {
        return $this->sentConversations()
            ->orWhere(function ($query) {
                $query->where('receiver_type', get_class($this))
                    ->where('receiver_id', $this->id);
            });
    }

    public function sentConversations()
    {
        return $this->morphMany(Conversation::class, 'sender');
    }

    public function receivedConversations()
    {
        return $this->morphMany(Conversation::class, 'receiver');
    }
}
