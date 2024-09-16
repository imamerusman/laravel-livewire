<?php

namespace App\Filament\AdminPanel\Resources\AppDevOrderResource\Pages;

use App\Filament\AdminPanel\Resources\AppDevOrderResource;
use App\Models\AppDevOrder;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

/**
 * @property AppDevOrder $record
 * */
class ViewAppDevOrder extends ViewRecord
{
    protected static string $resource = AppDevOrderResource::class;

    protected function getActions(): array
    {
        $record = $this->record;
        return [
            Action::make('Complete')
                ->hidden($record->status === AppDevOrder::COMPLETED)
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription('Are you sure you want to complete this order?')
                ->form(function (Form $form) {
                    return $form->schema([
                        MarkdownEditor::make('feedback')
                            ->required()
                    ]);
                })->action(function (array $data) use ($record) {
                    $this->record->update([
                        'status' => AppDevOrder::COMPLETED,
                        'feedback' => $data['feedback'],
                        'completed_at' => now(),
                    ]);

                    Notification::make()
                        ->title('App Development Order Completed')
                        ->body('Your app development order has been completed. Please check your app.')
                        ->success()
                        ->sendToDatabase($record->user);
                })
        ];
    }
}
