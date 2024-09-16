<?php

namespace App\Filament\AppPanel\Resources;

use App\Filament\AppPanel\Resources\NotificationResource\Pages\CreateNotification;
use App\Filament\AppPanel\Resources\NotificationResource\Pages\EditNotification;
use App\Filament\AppPanel\Resources\NotificationResource\Pages\ListNotifications;
use App\Filament\AppPanel\Resources\NotificationResource\Widgets\SalesByEachNotificationCategory;
use App\Models\Notifications\OtherNotification;
use App\Models\Notifications\ScheduleNotification;
use App\Notifications\ScheduleNotification as ScheduleNotificationJob;
use App\Services\OpenAI;
use Carbon\Carbon;
use Exception;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class NotificationResource extends Resource
{
    protected static ?string $model = ScheduleNotification::class;

    protected static ?string $slug = 'notifications';
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Notification';
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-bell';
    }

    public static function getNavigationLabel(): string
    {
        return 'AI Notifications';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(4)->schema([
                    Section::make([
                        TextInput::make('title')
                            ->hint(fn(?string $state, TextInput $component) => 'Left: '
                                . $component->getMaxLength() - strlen($state) . ' characters')
                            ->maxLength(50)
                            ->reactive()
                            ->required(),

                        TextInput::make('body')
                            ->hint(fn(?string $state, TextInput $component) => 'Left: '
                                . $component->getMaxLength() - strlen($state) . ' characters')
                            ->maxLength(255)
                            ->reactive()
                            ->required(),

                        DateTimePicker::make('scheduled_at')
                            ->label('Scheduled Date')
                            ->minDate(Carbon::now())
                            ->key('scheduled_at')
                            ->displayFormat('d M Y, H:m A')
                            ->reactive()
                            ->required(),

                        SpatieMediaLibraryFileUpload::make('media')
                            ->collection(ScheduleNotification::MEDIA_COLLECTION),

                    ])->columnSpan(2),
                    Placeholder::make('placeholder')
                        ->view('components.mockups')
                        ->label('Placeholder')
                        ->columnSpan(2),
                ])
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                TextColumn::make('body')
                    ->limit(30),
                SpatieMediaLibraryImageColumn::make('media')
                    ->collection(ScheduleNotification::MEDIA_COLLECTION),

                TextColumn::make('scheduled_at')
                    ->label('Scheduled Date')
                    ->date()
                    ->since()
                    ->formatStateUsing(function (string $state): string {
                        $data = Carbon::parse($state);
                        return $data->format('d-M-Y H:m') . ' | '
                            . $data->timezoneName . ' | ' . $data->diffForHumans();
                    }),

                TextColumn::make('state')
                    ->badge()
                    ->colors([
                        'secondary' => 'pending',
                        'success' => 'sent',
                        'danger' => 'failed',
                        'warning' => 'missed',
                    ]),
            ])
            ->actions([
                Action::make('Send Now')
                    ->action(function (ScheduleNotification $notification) {
                        foreach ($notification->user?->customers ?? [] as $customer) {
                            $customer->notify(new ScheduleNotificationJob($notification));
                        }
                    })
                    ->icon('heroicon-o-bell')
                    ->requiresConfirmation()
                    ->label(fn(ScheduleNotification $notification) => $notification->state === 'pending'
                        ? 'Send Now' : 'Resend Now')
                    ->visible(fn(ScheduleNotification $notification) => $notification->state === 'sent'),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('Send Now')
                    ->action(function (ScheduleNotification $notification) {
                        foreach ($notification->user?->customers ?? [] as $customer) {
                            $customer->notify(new ScheduleNotificationJob($notification));
                        }
                    })
                    ->requiresConfirmation()
                    ->label(fn(ScheduleNotification $notification) => $notification->state === 'pending'
                        ? 'Send Now' : 'Resend Now'),
                BulkAction::make('delete')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn(Collection $records) => $records->each(fn(ScheduleNotification $notification) => $notification->delete()))
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {

        return [
            'index' => ListNotifications::route('/'),
            'create' => CreateNotification::route('/create'),
            'edit' => EditNotification::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'body', 'user.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->user) {
            $details['User'] = $record->user->name;
        }

        return $details;
    }

    public static function getWidgets(): array
    {
        return [
            SalesByEachNotificationCategory::class
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user']);
    }

   /* public static function can(string $action, ?Model $record = null): bool
    {
        return !auth()->user()->is_admin;
    }*/
}
