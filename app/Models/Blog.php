<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Blog extends Model implements HasMedia
{
    use HasTags, InteractsWithMedia;

    const MEDIA_COLLECTION = "blog_media";

    protected $casts = [
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }
}
