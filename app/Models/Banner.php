<?php

namespace App\Models;

use App\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Banner extends Model implements HasMedia
{
    use BelongsToUser, InteractsWithMedia;

    protected $casts = [
      'created_at' => 'datetime',
      'updated_at' => 'datetime'
    ];

    protected function mediaUrl(): Attribute
    {
        return Attribute::make(get: fn() => url($this->getFirstMediaUrl()));
    }
}
