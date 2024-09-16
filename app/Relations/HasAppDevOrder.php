<?php

namespace App\Relations;

use App\Models\AppDevOrder;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasAppDevOrder {

    public function appDevOrder(): HasOne
    {
        return $this->hasOne(related: AppDevOrder::class);
    }
}
