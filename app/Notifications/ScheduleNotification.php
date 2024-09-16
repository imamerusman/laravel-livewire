<?php

namespace App\Notifications;

use App\Models\Notifications\ScheduleNotification as ScheduleNotificationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class ScheduleNotification extends Notification implements ShouldQueue
{
    use Queueable, Dispatchable;

    public ?Model $notifiable = null;

    public function __construct(protected ScheduleNotificationModel $notification)
    {
    }

    public function via(): string
    {
        return FirebaseChannel::class;
    }

    public function toCloudMessage($notifiable): FirebaseNotification
    {
        $this->notifiable = $notifiable;
        $media = $this->notification->getMedia(ScheduleNotificationModel::MEDIA_COLLECTION)->first();
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

    public function replaceVariable(string $text): string
    {
        $variables = [
            'customerName' => $this->notifiable?->name ?? '',
            'userName' => $this->notifiable?->user?->name ?? '',
            'companyName' => $this->notifiable?->user?->name ?? '',
            // Assuming there's a company associated with the event
            // ...
        ];
        foreach ($variables as $placeholder => $value) {
            $text = str_replace('{{' . $placeholder . '}}', $value, $text);
        }

        return $text;
    }

    public function sent(): void
    {
        $this->notification->markAs(ScheduleNotificationModel::PROCESSED);
    }

    public function failed(): void
    {
        $this->notification->markAs(ScheduleNotificationModel::FAILED);
    }
}
