<?php

namespace App\Filament\AppPanel\Resources\AppReviewResource\Pages;

use App\Filament\AppPanel\Resources\AppReviewResource;
use App\Models\AppReview;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanSubscription;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAppReviews extends ListRecords
{
    protected static string $resource = AppReviewResource::class;

    protected function getActions(): array
    {
        /**
         * @var Plan $plan
         * @var PlanSubscription $subscription
         */
        $user = auth()->user();
        $subscription = $user->activeSubscription();
        if ($subscription === false) {
            return [];
        }
        $appRevisionFeatureRemaining = $subscription?->getRemainingOf('revision');
        $lastAppReview = $user->appReviews()->orderByDesc('created_at')->first();
        $lastReviewIsProcessed = !isset($lastAppReview)
            || $lastAppReview->status === AppReview::APPROVED
            || $lastAppReview->status === AppReview::REJECTED
            || $lastAppReview->status === AppReview::WITHDREW;

        return [
            CreateAction::make()
                ->successRedirectUrl(AppReviewResource::getUrl())
                ->visible($appRevisionFeatureRemaining > 0 && $lastReviewIsProcessed)
                ->hidden($appRevisionFeatureRemaining <= 0),
        ];
    }
}
