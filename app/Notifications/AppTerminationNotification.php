<?php

namespace App\Notifications;

use App\Models\Events\AppTerminationEvent;
use App\Models\Notifications\NotificationTypes;
use App\Models\Notifications\OtherNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use InvalidArgumentException;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class AppTerminationNotification extends Notification implements ShouldQueue
{
    use Queueable, Dispatchable, SerializesModels;

    public function __construct(protected OtherNotification $notification, protected AppTerminationEvent $event)
    {
        if ($this->notification->type !== NotificationTypes::AppTerminationReminder) {
            throw new InvalidArgumentException('Notification type is not AppTerminationReminder');
        }
    }

    public function via(): string
    {
        return FirebaseChannel::class;
    }

    public function toCloudMessage(): FirebaseNotification
    {
        $media = $this->notification->getMedia(OtherNotification::MEDIA_COLLECTION)->first();
        return FirebaseNotification::create(
            title: $this->replaceVariable($this->notification->title),
            body: $this->replaceVariable($this->notification->body),
            imageUrl: $media?->getFullUrl()
        );
    }

    public function getPayloadData() : array
    {
        return [
            'type' => get_class($this->notification),
            'id' => $this->notification->id,
        ];
    }
    public function getCustomer()
    {
        return $this->event->customer;
    }
    /**
     * @param string $text eg: {{customerName}}
     * @return string
     */
    public function replaceVariable(string $text): string
    {
        $variables = [
            'customerName' => $this->event->customer->name,
            'userName' => $this->event->customer->user->name,
            'companyName' => $this->event->customer->user->name, // Assuming there's a company associated with the event
            // ...
        ];
        foreach ($variables as $placeholder => $value) {
            $text = str_replace('{{' . $placeholder . '}}', $value, $text);
        }

        return $text;
    }

    public function failed(): void
    {
        $this->event->markAsFailed();
    }

    public function sent(): void
    {
        $this->event->markAsResolved();
    }
}
