<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class UserDeletedEvent
{
    use Dispatchable;

    public function __construct(protected User $user)
    {
    }
}
