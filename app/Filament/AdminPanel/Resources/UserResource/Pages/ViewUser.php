<?php

namespace App\Filament\AdminPanel\Resources\UserResource\Pages;

use App\Events\UserDeletedEvent;
use App\Filament\AdminPanel\Resources\UserResource;
use App\Models\Customer;
use App\Models\User;
use Closure;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

/**
 * @property User $record
 * */
class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make('Delete')
                ->requiresConfirmation()
                ->modalDescription("Are you sure you want to delete {$this->record->name} and all related data?")
                ->action(self::deleteUserAndRelatedData($this))
        ];
    }

    public static function deleteUserAndRelatedData(Component $component): Closure
    {
        return function (User $user) use ($component) {
            if ($user->is_admin) {
                Notification::make()
                    ->danger()
                    ->title('User deletion failed')
                    ->body('You cannot delete an admin user.')
                    ->send();
                return;
            }

            try {
                $user->cancelCurrentSubscription();
                $user->getPlanSubscriptions()->delete();
                $user->banners()->delete();
                $user->appReviews()->delete();
                $user->customers->map(function (Customer $customer) {
                    $customer->cartAbandonmentEvents()->delete();
                    $customer->appTerminationEvents()->delete();
                    $customer->notificationAnalytics()->delete();
                    $customer->conversations()->delete();
                    $customer->wishList()->delete();
                });
                $user->customers()->delete();
                $user->firebaseCredentials()->delete();
                $user->tokens()->delete();
                $user->otherNotifications()->delete();
                $user->scheduleNotifications()->delete();
                $user->conversations()->delete();
                $user->appPreference()->delete();
                $user->delete();

                Notification::make()
                    ->success()
                    ->title('User deleted successfully')
                    ->body('User and all related data deleted successfully.')
                    ->send();
                event(new UserDeletedEvent($user));

                $component->redirect(UserResource::getUrl());

            } catch (\Exception $exception) {
                Notification::make()
                    ->danger()
                    ->title('User deletion failed')
                    ->body('User and all related data deletion failed. See logs for more details.')
                    ->send();

                Log::error($exception->getMessage(), $exception->getTrace());
            }
        };
    }
}
