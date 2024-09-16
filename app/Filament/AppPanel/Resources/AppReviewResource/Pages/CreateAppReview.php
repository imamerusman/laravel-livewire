<?php

namespace App\Filament\AppPanel\Resources\AppReviewResource\Pages;

use App\Filament\AppPanel\Resources\AppReviewResource;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanSubscription;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class CreateAppReview extends CreateRecord
{
    protected static string $resource = AppReviewResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id;
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * @throws Halt
     */
    protected function beforeCreate(): void
    {
        /** @var Plan $plan
         * @var PlanSubscription $subscription
         */
        $subscription = auth()->user()->activeSubscription();
        $appRevisionFeature = $subscription->plan->features()->where('code', 'revision')->first();
        if ($appRevisionFeature->limit > 0) {
            $subscription->consumeFeature('revision', 1);
        } else {
            Notification::make()
                ->warning()
                ->title('App Revision Limit Reached')
                ->body('You have reached the limit of app revisions. Please upgrade your plan.')
                ->persistent()
                ->actions([
                    Action::make('Upgrade')
                        ->button()
                        ->url(route('pricing'), shouldOpenInNewTab: true),
                ])
                ->send();

            $this->halt();
        }

    }

    protected function afterCreate(): void
    {
        $superAdmin = User::query()->role(['super_admin'])->first();

        if (filled($superAdmin)) {
            Notification::make()
                ->success()
                ->title('New App Review Arrived')
                ->body('A new app review has been submitted.')
                ->actions([
                    Action::make('Check it out')
                        ->url('/admin/app-reviews')
                ])
                ->sendToDatabase($superAdmin);
        }
    }

    protected function getActions(): array
    {
        return [

        ];
    }
}
