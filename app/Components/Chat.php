<?php

namespace App\Components;

use App\Models\Conversation;
use Illuminate\View\Component;

class Chat extends Component
{
    public function __construct(protected Conversation $conversation)
    {
    }

    public function render(): string
    {
        return $this->view('components.chat', [
            'conversation' => $this->conversation,
        ]);
    }
}
