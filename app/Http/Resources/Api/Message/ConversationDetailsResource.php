<?php

namespace App\Http\Resources\Api\Message;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Conversation */
class ConversationDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'messages' => MessageListResource::collection($this->messages),
        ];
    }
}
