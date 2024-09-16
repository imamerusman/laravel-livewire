<?php

namespace App\Http\Resources\Api\Message;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

/** @mixin Conversation */
class ConversationListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'recipient' => [
                'id' => $this->recipientUser()->id,
                'name' => $this->recipientUser()->name,
            ],
            'last_message' => [
                'message' => Str::limit(
                    isset($lastMessage)
                        ? $this->last_message
                        : 'Start a conversation'
                    , 50),
                'created_at' => $this->last_message_at?->diffForHumans() ?? $this->created_at->diffForHumans(),
            ],
            'is_read' => $this->is_read,
        ];
    }
}
