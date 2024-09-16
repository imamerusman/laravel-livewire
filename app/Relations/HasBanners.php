<?php

namespace App\Relations;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasBanners
{
    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class);
    }
}
