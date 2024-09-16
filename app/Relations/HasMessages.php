<?php

namespace App\Relations;

use App\Models\Message;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasMessages {

    public function messages(): HasMany
    {
        return $this->hasMany(related: Message::class);
    }
}
