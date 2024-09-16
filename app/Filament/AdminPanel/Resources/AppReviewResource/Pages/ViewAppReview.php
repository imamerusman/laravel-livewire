<?php

namespace App\Filament\AdminPanel\Resources\AppReviewResource\Pages;

use App\Filament\AdminPanel\Resources\AppReviewResource;
use App\Models\AppReview;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewAppReview extends ViewRecord
{
    protected static string $resource = AppReviewResource::class;

    protected function getActions(): array
    {
        return [
            ActionGroup::make([

                Action::make('start')
                    ->label('Put in Progress')
                    ->color('primary')
                    ->visible(fn(AppReview $appReview) => $appReview->status === AppReview::PENDING)
                    ->action(function (AppReview $appReview) {
                        $appReview->status = AppReview::IN_PROGRESS;
                        $appReview->save();
                        $user = $appReview->user;

                        Notification::make()
                            ->success()
                            ->title('Your review is in progress')
                            ->body("Your review for $appReview->title is in progress. We will notify you when it is completed.")
                            ->sendToDatabase($user);
                    }),

                Action::make('Reject')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('Please provide feedback to the user')
                    ->form(function (Form $form) {
                        return $form->schema([
                            MarkdownEditor::make('feedback')
                                ->label('Reason')
                                ->required()
                        ]);
                    })
                    ->visible(fn(AppReview $appReview) => $appReview->status === AppReview::PENDING)
                    ->action(function (array $data, AppReview $appReview) {
                        $appReview->feedback = $data['feedback'];
                        $appReview->status = AppReview::REJECTED;
                        $appReview->save();
                        $user = $appReview->user;
                        $sub = $user->activeSubscription();
                        $sub?->unconsumeFeature('revision', 1);

                        Notification::make()
                            ->danger()
                            ->title('Your review is rejected')
                            ->body("Your review for $appReview->title is rejected.
                                Please check your feedback. And your remaining revision is {$sub->getRemainingOf('revision')}.")
                            ->sendToDatabase($user);
                    }),

                Action::make('Approve')
                    ->color('success')
                    ->visible(fn(AppReview $appReview) => $appReview->status === AppReview::IN_PROGRESS)
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-check-circle')
                    ->modalDescription('Please provide feedback to the user')
                    ->form(function (Form $form) {
                        return $form->schema([
                            MarkdownEditor::make('feedback')
                                ->required()
                        ]);
                    })
                    ->action(function (array $data, AppReview $appReview) {
                        $appReview->feedback = $data['feedback'];
                        $appReview->status = AppReview::APPROVED;
                        $appReview->save();
                        $user = $appReview->user;

                        Notification::make()
                            ->success()
                            ->title('Your review is approved')
                            ->body("Your review for $appReview->title is approved. Thank you for your feedback.")
                            ->sendToDatabase($user);
                    }),
            ])->label('Actions'),
        ];
    }
}
