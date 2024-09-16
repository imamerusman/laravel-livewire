<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DirectMessageNotification extends Notification implements ShouldQueue
{
    use Queueable, Dispatchable;

    public function __construct(
        private readonly string $title,
        private readonly string $message,
        private readonly ?Media $media = null,
    )
    {
    }

    public function via(object $notifiable): string
    {
        return FirebaseChannel::class;
    }

    public function toCloudMessage($notifiable): FirebaseNotification
    {
        return FirebaseNotification::create(
            title: $this->title,
            body: $this->message,
            imageUrl: $this->media?->getFullUrl(Message::MEDIA_COLLECTION),
        );
    }


}
