<?php

namespace App\Console\Commands;

use App\Jobs\RenewSubscriptionJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class RenewSubscriptionsCommand extends Command
{
    protected $signature = 'renew:subscriptions';

    protected $description = 'Command description';

    public function handle(): void
    {
        $users = User::query()->whereHas('getPlanSubscriptions', function (Builder $query) {
            $query->where('expires_on', '<=', Carbon::now());
        })->get();

        foreach ($users as $user) {
            RenewSubscriptionJob::dispatch($user);
        }
    }
}
