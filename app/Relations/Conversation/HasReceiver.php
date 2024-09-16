<?php

namespace App\Relations\Conversation;

trait HasReceiver
{
    public function receiver()
    {
        return $this->morphTo();
    }
}
