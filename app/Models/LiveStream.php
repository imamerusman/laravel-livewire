<?php

namespace App\Models;

use App\Jobs\LiveStreams\ApiVideo;
use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    protected $casts = [
        'products' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::created(function (self $item) {
            ApiVideo\CreateJob::dispatch(liveStream: $item);
        });

        static::updated(function (self $item) {
            ApiVideo\UpdatedJob::dispatch(liveStream: $item);
        });

        static::deleted(function (self $item) {
            ApiVideo\DeleteJob::dispatch(liveStreamId: $item->stream_id);
        });

    }
}
