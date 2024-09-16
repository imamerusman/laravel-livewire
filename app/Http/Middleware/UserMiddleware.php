<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->is_admin) {
            return $next($request);
        }
        Notification::make()
            ->title('Uh oh!')
            ->body('You must login before you can continue.')
            ->warning()
            ->send();
        return redirect()->to('/');
    }
}
