<?php

namespace App\Models\Events;

use App\Relations\BelongsToCustomer;
use Illuminate\Database\Eloquent\Model;

class AppTerminationEvent extends Model
{
    use BelongsToCustomer, StatusResolver;
}
