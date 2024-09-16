<?php

namespace App\Models;

use App\Relations\BelongsToCustomer;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    use BelongsToCustomer;

    protected $casts = [
        'items' => 'collection',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
