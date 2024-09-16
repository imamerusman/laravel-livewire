<?php

namespace App\Filament\AppPanel\Resources\NotificationResource\Pages;

use App\Filament\AppPanel\Resources\NotificationResource;
use App\Http\Controllers\OpenAiController;
use App\Models\Notifications\NotificationTypes;
use App\Models\Notifications\OtherNotification;
use Filament\Actions\Action;
use App\Services\OpenAI;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ListNotifications extends ListRecords
{

    /**
     * @var NotificationTypes[] NOTIFICATION_TYPES List of notification types.
     */
    private const NOTIFICATION_TYPES = [
        NotificationTypes::AbandonedCartReminder,
        NotificationTypes::RecentProductReminder,
        NotificationTypes::ShoppingTimeReminder,
        NotificationTypes::AppTerminationReminder,
    ];
    protected static string $resource = NotificationResource::class;
    protected static ?string $title = 'Schedule Notifications';
    protected static string $view = 'filament.app-panel.resources.notifications.pages.list-notifications';
    public ?string $availableOptions = '';
    public ?array $AbandonedCartReminderData = [];
    public ?array $RecentProductReminderData = [];
    public ?array $ShoppingTimeReminderData = [];
    public ?array $AppTerminationReminderData = [];

    public function mount(): void
    {
        parent::mount();

        foreach (self::NOTIFICATION_TYPES as $type) {
            $this->initializeNotification($type);
        }
    }

    private function initializeNotification(NotificationTypes $type): void
    {
        $notification = $this->getOtherNotificationModel($type);
        $form = $this->{$type->name . 'Form'};
        /** @var Form $form */
        $form->fill([
            'enabled' => $notification->enabled,
            'title' => $notification->title,
            'body' => $notification->body,
            'media' => $notification->getFirstMediaUrl(OtherNotification::MEDIA_COLLECTION),
            'after' => $notification->meta_data['after'] ?? null,
        ]);
    }

    private function getOtherNotificationModel(NotificationTypes $type): ?OtherNotification
    {
        return OtherNotification::where('user_id', auth()->id())
            ->firstOrCreate(
                [
                    'type' => $type,
                    'user_id' => auth()->id(),
                ],
                [
                    'title' => '',
                    'body' => '',
                ]
            );
    }

    public function saveNotificationForm(string $type): void
    {
        $type = NotificationTypes::from($type);
        /** @var Form $form */
        $form = $this->{$type->name . 'Form'};
        /** @var OtherNotification $model */
        $model = $form->model;
        $state = $form->getState();
        $data = [
            'enabled' => $state['enabled'] ?? false,
            'title' => $state['title'] ?? '',
            'body' => $state['body'] ?? '',
            'meta_data' => [
                'after' => $state['after'] ?? '1',
            ]
        ];
       /*if ($type === NotificationTypes::AppTerminationReminder) {
            $data['meta_data'] = [
                'after' => $state['after'] ?? '1',
            ];
        }*/
        if (filled($state['media'])) {
            /** @var TemporaryUploadedFile $media */
            $file = collect($state['media'])->first();

            try {
                $model->addMedia($file)->toMediaCollection(OtherNotification::MEDIA_COLLECTION);
            } catch (FileDoesNotExist|FileIsTooBig $e) {
                Log::error($e->getMessage(), $e->getTrace());
                $this->sendNotification(message: $e->getMessage(), status: 'error');
            }
        }
        $model->update($data);

        $notificationLabel = ucfirst(str_replace('_', ' ', $type->value));
        $this->sendNotification(message: "$notificationLabel notification updated successfully.");
    }

    private function sendNotification(string $message, string $title = '', string $status = 'success'): void
    {
        Notification::make()
            ->status($status)
            ->title($title)
            ->body($message)
            ->send();
    }

    public function AbandonedCartReminderForm(Form $form): Form
    {
        return $this->defineNotificationForm(
            form: $form,
            type: NotificationTypes::AbandonedCartReminder,
            title: 'Abandoned Cart Notifications',
        );
    }

    private function defineNotificationForm(
        Form              $form,
        NotificationTypes $type,
        string            $title = '',

    ): Form
    {
        $action = "getResponse('{$type->value}')";
        return $form->schema([
            Section::make()->schema([
                Grid::make(12)->schema([
                    Placeholder::make("")
                        ->label(new HtmlString('
                           <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-white">' . $title . '</h3>
                        '))
                        ->disabled()
                        ->columnSpan(10),
                    Toggle::make("enabled")
                        ->hiddenLabel()
                        ->reactive()
                        ->columnSpan(2),
                ]),

                Grid::make(12)->schema([
                    Placeholder::make("")
                        ->label(new HtmlString('
                           <h3 class="text-base font-semibold leading-7 text-gray-900 dark:text-white"></h3>
                        '))
                        ->disabled()
                        ->columnSpan(9),
                    Placeholder::make("")
                        ->label(new HtmlString('
                            <button type="button" style="margin-left: 15px"  wire:click='.$action.' class=" py-2 px-4 flex justify-center items-center  bg-primary-500 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none   rounded-lg max-w-md">
                               <span wire:loading.remove wire:target='.$action.'>Autofill</span>
                                <span wire:loading wire:target='.$action.'>
                                    <svg width="20" height="20" fill="currentColor" class="mr-2 animate-spin" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M526 1394q0 53-37.5 90.5t-90.5 37.5q-52 0-90-38t-38-90q0-53 37.5-90.5t90.5-37.5 90.5 37.5 37.5 90.5zm498 206q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-704-704q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm1202 498q0 52-38 90t-90 38q-53 0-90.5-37.5t-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-964-996q0 66-47 113t-113 47-113-47-47-113 47-113 113-47 113 47 47 113zm1170 498q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-640-704q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm530 206q0 93-66 158.5t-158 65.5q-93 0-158.5-65.5t-65.5-158.5q0-92 65.5-158t158.5-66q92 0 158 66t66 158z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                        '))
                        ->disabled()
                        ->columnSpan(3),
                ])
                    ->hidden(fn($get) => !$get('enabled'))
                    ->disabled(fn($get) => !$get('enabled'))
                ,
                TextInput::make("title")
                    ->label('Title')
                    ->required()
                    ->hidden(fn($get) => !$get('enabled'))
                    ->disabled(fn($get) => !$get('enabled')),
                Textarea::make("body")
                    ->label('Content')
                    ->rows(2)
                    ->required()
                    ->hidden(fn($get) => !$get('enabled'))
                    ->disabled(fn($get) => !$get('enabled')),

                SpatieMediaLibraryFileUpload::make('media')
                    ->collection(OtherNotification::MEDIA_COLLECTION)
                    ->hidden(fn($get) => !$get('enabled'))
                    ->disabled(fn($get) => !$get('enabled')),

                Select::make('after')
                    ->label('After')
                    ->options([
                        '0' => 'Immediately',
                        '1' => '1 hour',
                        '2' => '2 hours',
                        '3' => '3 hours',
                        '4' => '4 hours',
                        '5' => '5 hours',
                        '6' => '6 hours',
                        '7' => '7 hours',
                        '8' => '8 hours',
                        '9' => '9 hours',
                        '10' => '10 hours',
                        '11' => '11 hours',
                        '12' => '12 hours',
                        '13' => '13 hours',
                        '14' => '14 hours',
                        '15' => '15 hours',
                        '16' => '16 hours',
                        '17' => '17 hours',
                        '18' => '18 hours',
                        '19' => '19 hours',
                        '20' => '20 hours',
                        '21' => '21 hours',
                        '22' => '22 hours',
                        '23' => '23 hours',
                        '24' => '24 hours',
                    ])
                    ->searchable()
                    ->live()
                    /*->visible($type === NotificationTypes::AppTerminationReminder)*/
                    ->hidden(fn($get) => !$get('enabled'))
                    ->disabled(fn($get) => !$get('enabled'))
                    ->required()
            ])
        ])
            ->model($this->getOtherNotificationModel($type))
            ->statePath("{$type->name}Data");
    }

    public function RecentProductReminderForm(Form $form): Form
    {
        return $this->defineNotificationForm(
            form: $form,
            type: NotificationTypes::RecentProductReminder,
            title: 'Notifications About Recently Added Products',
        );
    }

    public function ShoppingTimeReminderForm(Form $form): Form
    {
        return $this->defineNotificationForm(
            form: $form,
            type: NotificationTypes::ShoppingTimeReminder,
            title: 'Notifications About Shopping time'
        );
    }

    public function AppTerminationReminderForm(Form $form): Form
    {
        return $this->defineNotificationForm(
            form: $form,
            type: NotificationTypes::AppTerminationReminder,
            title: 'Send Notifications when user terminates the App'
        );
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('user_id', auth()->id());
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
        ];
    }

    protected function getForms(): array
    {
        $formMethods = [];

        foreach (self::NOTIFICATION_TYPES as $type) {
            $formMethodName = "{$type->name}Form";
            $formMethods[] = $formMethodName;
        }
        return $formMethods;
    }

    public function getResponse(string $value): void
    {
        $type = NotificationTypes::from($value);
        $prompt = $this->getPrompt($type);
        $model = $this->getOtherNotificationModel($type);
        $response = OpenAI::getAiResponse($prompt);
        $form = $this->{$type->name . 'Form'};
        /** @var Form $form */
        $form->fill([
            'title' => $response['title'],
            'body' => $response['description'],
            'enabled' => true,
            'media' => $model->getFirstMediaUrl(OtherNotification::MEDIA_COLLECTION),
            'after' => $model->meta_data['after'] ?? '1',
        ]);
    }

    private function getPrompt(NotificationTypes $type): string
    {
        return match ($type) {
            NotificationTypes::AbandonedCartReminder => 'A user has added a product to his cart but did not purchase it. We need to send him a notification about the product he added to his cart.',
            NotificationTypes::RecentProductReminder => 'A user has recently added a product to his cart. We need to send him a notification about the product he added to his cart.',
            NotificationTypes::ShoppingTimeReminder => 'A user has been shopping for a while. We need to send him a notification about the products he has added to his cart.',
            NotificationTypes::AppTerminationReminder => 'A user has terminated his mobile app now we need to send him a notification about greeting him back.',
        };
    }

}
