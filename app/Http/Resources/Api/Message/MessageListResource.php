<?php

namespace App\Http\Resources\Api\Message;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Message */
class MessageListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->loadMissing(['media', 'sender']);
        $media = $this->getMedia(Message::MEDIA_COLLECTION)->first();
        return [
            'id' => $this->id,
            'message' => $this->content,
            'media' => filled($media) ? [
                'url' => $media?->getFullUrl(),
                'size' => $media?->human_readable_size,
                'type' => $media?->type,
            ] : null,
            'created_at' => $this->created_at?->diffForHumans(),
            'sent_by_me' => $this->sender->id !== $request->user()->id,
        ];
    }
}
