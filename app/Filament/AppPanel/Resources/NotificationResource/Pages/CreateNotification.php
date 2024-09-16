<?php

namespace App\Filament\AppPanel\Resources\NotificationResource\Pages;

use App\Filament\AppPanel\Resources\NotificationResource;
use App\Models\Notifications\OtherNotification;
use App\Services\OpenAI;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateNotification extends CreateRecord
{
    protected static string $resource = NotificationResource::class;

    protected static string $view = 'filament.app-panel.resources.notifications.pages.create-notifications';

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getURL('index');
    }

    public function setTimeZoneOnFormPropertyTimeZone(string $timezone): void
    {
        $component = $this->form->getComponent('scheduled_at');
        if (filled($component) && $component instanceof DateTimePicker) {
            $component->timezone($timezone);
            $this->form->fill([
                'scheduled_at' => now($timezone)
            ]);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id;
        $data['scheduled_at'] = Carbon::parse($data['scheduled_at'])->setTimezone('UTC');
        return $data;
    }


    public function getAiResponse(): void
    {
        $prompt = "A user will be notified on the scheduled time.";
        if (config('app.openai_key') !== null){
            Notification::make()
            ->title('Uh oh!')
            ->body('You provided an OpenAI key. Please remove it from the .env file before continuing.')
            ->warning()
            ->send();
            return;
        }
        $response = OpenAI::getAiResponse($prompt);
        /** @var Form $form */
        $this->form->fill([
            'title' => $response['title'],
            'body' => $response['description'],
            'scheduled_at' => now()->addDay(2)->format('Y-m-d\TH:i'),
        ]);
    }
}
