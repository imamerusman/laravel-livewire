<?php

namespace App\Models\Notifications;

use App\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OtherNotification extends Model implements HasMedia
{
    use BelongsToUser, InteractsWithMedia, HasFactory;

    const MEDIA_COLLECTION = 'other_notification_media';
    protected $casts = [
        'type' => NotificationTypes::class,
        'meta_data' => 'array',
    ];
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', true);
    }
}
