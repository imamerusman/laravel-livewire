<?php

namespace App\Relations;

use App\Models\UserAppPreference;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasAppPreference
{
    public function appPreference(): HasMany
    {
        return $this->hasMany(UserAppPreference::class);
    }
}
