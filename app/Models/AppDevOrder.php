<?php

namespace App\Models;

use App\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AppDevOrder extends Model implements HasMedia
{
    use BelongsToUser, InteractsWithMedia;

    const PENDING = 'pending';
    const IN_PROGRESS = 'in_progress';
    const COMPLETED = 'completed';
    const REJECTED = 'rejected';
    const WITHDREW = 'withdrew';
    const MEDIA_COLLECTION = 'app_dev_order_files';
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

}
