<?php

namespace App\Models\Notifications;

use App\Relations\BelongsToUser;
use Database\Factories\ScheduleNotificationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ScheduleNotification extends Model implements HasMedia
{
    use BelongsToUser, InteractsWithMedia, HasFactory;
    const PENDING = 'pending';
    const PROCESSED = 'processed';
    const SCHEDULED = 'scheduled';
    const FAILED = 'failed';
    /**
     * Notification was missed because `scheduled_at` was in the past
     */
    const MISSED = 'missed';

    const MEDIA_COLLECTION = 'schedule_notification_media';

    protected $casts = [
        'scheduled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @throws MessagingException
     * @throws FirebaseException
     */
    public function send(): void
    {
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where('scheduled_at', '<', now());
    }
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('scheduled_at', '>=', now());
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('state', self::PENDING);
    }
    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('state', self::SCHEDULED);
    }

    public function scopeSent(Builder $query): Builder
    {
        return $query->where('state', self::PROCESSED);
    }

    public function markAs(string $type): void
    {
        $this->update(['state' => $type]);
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('state', self::FAILED);
    }
}
