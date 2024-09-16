<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;

class AssignUserRole
{
    public function __construct()
    {
    }

    public function handle(Registered $event): void
    {
        /** @var User $user */
        $user = $event->user;
        $user->assignRole(Role::findByName('user'));
    }
}
