<?php

namespace App\Models;

use App\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AppReview extends Model implements HasMedia
{
    use BelongsToUser, InteractsWithMedia;
    const PENDING = 'pending';
    const IN_PROGRESS = 'in_progress';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    const WITHDREW = 'withdrew';

    const MEDIA_COLLECTION = 'app_review_files';
}
