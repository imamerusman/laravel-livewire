<?php

namespace App\Models\Events;

use App\Relations\BelongsToCustomer;
use Illuminate\Database\Eloquent\Model;

class CartAbandonmentEvent extends Model
{
    use BelongsToCustomer, StatusResolver;
}
