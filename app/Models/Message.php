<?php

namespace App\Models;

use App\Relations\BelongsToConversation;
use App\Relations\Conversation\HasSender;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use BelongsToConversation, HasSender, InteractsWithMedia;

    const MEDIA_COLLECTION = 'messages';
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getSentAttribute(): string
    {
        return $this->created_at->diffForHumans(short: true);
    }
}
