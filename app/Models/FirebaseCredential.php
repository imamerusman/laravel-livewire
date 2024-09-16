<?php

namespace App\Models;

use App\Observers\FirebaseCredentialsObserver;
use App\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class FirebaseCredential extends Model
{
    use BelongsToUser;

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    protected array $observers = [
        FirebaseCredentialsObserver::class
    ];
}
