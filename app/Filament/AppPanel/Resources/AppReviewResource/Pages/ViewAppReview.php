<?php

namespace App\Filament\AppPanel\Resources\AppReviewResource\Pages;

use App\Filament\AppPanel\Resources\AppReviewResource;
use App\Models\AppReview;
use App\Models\Plans\PlanSubscription;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

/**
 * @property-read AppReview $record
 * */
class ViewAppReview extends ViewRecord
{
    protected static string $resource = AppReviewResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->visible($this->record->status === 'withdrew' || $this->record->status === 'pending')
                ->action(function () {
                    /** @var PlanSubscription $activeSubscription */
                    $activeSubscription = auth()->user()->activeSubscription();
                    $activeSubscription->unconsumeFeature('revision', 1);
                    $this->record->delete();
                    $this->redirect(AppReviewResource::getUrl());
                }),
        ];
    }
}
