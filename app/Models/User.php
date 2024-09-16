<?php

namespace App\Models;

use App\Interfaces\HasMessages;
use App\Relations\HasAppDevOrder;
use App\Relations\HasAppPreference;
use App\Relations\HasAppReviews;
use App\Relations\HasBanners;
use App\Relations\HasCustomers;
use App\Relations\HasFirebaseCredentials;
use App\Relations\HasOtherNotifications;
use App\Relations\HasScheduleNotifications;
use App\Traits\Messagable;
use App\Traits\Plans\HasPlans;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Auth\MustVerifyEmail as Verifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use function Illuminate\Events\queueable;

class User extends Authenticatable implements
    FilamentUser,
    MustVerifyEmail,
    HasMessages
{
    use
        Billable,
        HasApiTokens,
        HasFactory,
        Notifiable,
        HasScheduleNotifications,
        HasOtherNotifications,
        HasRoles,
        HasFirebaseCredentials,
        HasCustomers,
        HasAppReviews,
        HasBanners,
        Verifiable,
        Messagable,
        HasPlans,
        HasAppPreference,
        HasAppDevOrder;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::updated(queueable(function (self $customer) {
            try {
                if ($customer->hasStripeId()) {
                    $customer->syncStripeCustomerDetails();
                } else {
                    $customer->createAsStripeCustomer();
                }
            } catch (\Exception $e) {
                logger()->error($e->getMessage());
            }
        }));
    }

    public function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->hasRole('super_admin'),
        );
    }

    public function hasPlan(): bool
    {
        return true;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    protected function bootIfNotBooted(): void
    {
        parent::bootIfNotBooted();
        if (!$this->hasVerifiedEmail()) {
            $this->attributes['email_verified_at'] = now();
        }
    }

    public function hasCompletedAppPreferencesSteps(): bool
    {
        return true;
    }

    public function activeSubscription(): bool
    {
        return false;
    }

    public function appPreference(): HasOne
    {
        return $this->hasOne(UserAppPreference::class);
    }
}
