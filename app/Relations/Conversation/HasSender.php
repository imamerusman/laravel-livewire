<?php

namespace App\Relations\Conversation;

trait HasSender
{
    public function sender()
    {
        return $this->morphTo();
    }
}
