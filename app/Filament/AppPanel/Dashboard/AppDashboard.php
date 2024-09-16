<?php

namespace App\Filament\AppPanel\Dashboard;

use App\Models\AppDevOrder;
use App\Models\User;
use Filament\Pages\Dashboard;
use Illuminate\Support\Facades\DB;

class AppDashboard extends Dashboard
{
    protected static string $view = 'filament.app-panel.pages.dashboard';

    public bool $firebaseCredentialsConfigured = false;
    public bool $personalAccessTokenExists = false;
    public ?AppDevOrder $appDevOrder = null;
    public bool $stripeConfigured = false;

    public function mount(): void
    {
        $user = auth()->user();
        $this->personalAccessTokenExists = DB::table('personal_access_tokens')
            ->where([
                'tokenable_type' => User::class,
                'tokenable_id' => $user->id,
            ])->exists();

        $this->firebaseCredentialsConfigured = $user->firebaseCredentials()->exists();
        $this->appDevOrder = $user->appDevOrder;
        $this->stripeConfigured = filled($user->defaultPaymentMethod());
    }
}
