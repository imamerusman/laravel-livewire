<?php

namespace App\Relations;

use App\Models\AppReview;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasAppReviews
{
    public function appReviews(): HasMany
    {
        return $this->hasMany(AppReview::class);
    }
}
