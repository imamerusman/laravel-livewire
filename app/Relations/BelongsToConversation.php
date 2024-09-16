<?php


namespace App\Relations;


use App\Models\Conversation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToConversation {
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(related: Conversation::class);
    }
}
