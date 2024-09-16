<?php

namespace App\Interfaces;

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface HasMessages
{
    public function sendMessage(Model $to, string|UploadedFile $content): array;

    public function findOrCreateConversation(Model $to): Conversation;

    public function conversations();
}
