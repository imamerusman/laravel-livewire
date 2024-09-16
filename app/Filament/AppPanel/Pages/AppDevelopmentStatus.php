<?php

namespace App\Filament\AppPanel\Pages;

use App\Filament\AdminPanel\Resources\AppDevOrderResource as AdminAppDevOrderResource;
use App\Models\AppDevOrder;
use App\Models\FigmaDesign;
use App\Models\User;
use App\Models\UserAppPreference;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;

/**
 * @property Form $form
 */
class AppDevelopmentStatus extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'App Management';
    protected static string $view = 'filament.app-panel.pages.app-development-status';

    public ?AppDevOrder $appOrder = null;
    public ?array $data = [];

    public function mount(): void
    {
        $this->appOrder = AppDevOrder::query()->firstOrCreate([
            'user_id' => auth()->id(),
        ], [
            'name' => 'App Development Request from ' . auth()->user()->name,
            'description' => 'Please describe your app development request here.',
            'status' => AppDevOrder::PENDING
        ]);
        /** @var UserAppPreference $appPreference */

        $this->form->fill([
            'name' => $this->appOrder->name,
            'description' => $this->appOrder->description,
        ]);

        if (filled($this->appOrder->completed_at)) {
            return;
        }
        if (filled($this->appOrder->started_at)
            && $this->appOrder->status == AppDevOrder::IN_PROGRESS) {
            $daysLeftSinceStarted = now()->diffInDays($this->appOrder->started_at);

            Notification::make()
                ->title('App Development Order In Progress')
                ->body("Your app development order has been started. You will be notified when it is completed in $daysLeftSinceStarted days.")
                ->send();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            /*->disabled(function (?AppDevOrder $record) {
                return in_array($record?->status, [
                    AppDevOrder::IN_PROGRESS,
                    AppDevOrder::COMPLETED,
                    AppDevOrder::REJECTED,
                    AppDevOrder::WITHDREW
                ]);
            })*/
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name'),
                        MarkdownEditor::make('description'),

                        Checkbox::make('developer_accounts_created')
                            ->required()
                            ->hint('You Need to Create Both IOS, Android Console Account')
                            ->hintAction(
                                FormAction::make('How To Create?')
                                    ->requiresConfirmation()
                                    ->modalDescription('')
                                    ->modalHeading('')
                                    ->modalSubmitActionLabel('Done')
                                    ->modalCancelActionLabel('Go Back')
                                    ->modalWidth('lg')
                                    ->modalContent(view('components.developer-account.documentation'))
                            )
                            ->label('Developer Accounts Created ?'),
                    ]),
            ])->statePath('data');
    }

    public function infoList(Infolist $infolist): Infolist
    {
        /**
         * @var UserAppPreference $appPreference ;
         */
        $appPreference = auth()->user()->appPreference;
        $appPreference?->load(['media', 'figmaDesign.media']);
        if (!$appPreference) {
            $appPreference = new UserAppPreference();
        }
        return $infolist
            ->record($appPreference)
            ->schema([
                InfoSection::make('Your App Preferences:')
                    ->columns(12)
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('media')
                            ->label('Application Logo')
                            ->columnSpan(6)
                            ->collection(UserAppPreference::MEDIA_COLLECTION),

                        TextEntry::make('color')
                            ->label('Application Primary Color')
                            ->columnSpan(6)
                            ->badge()
                            ->color(fn(UserAppPreference $appPreference) => Color::hex($appPreference->color))
                            ->copyable(),

                        SpatieMediaLibraryImageEntry::make('figmaDesign.media')
                            ->label('Selected Theme')
                            ->columnSpan(12)
                            ->collection(FigmaDesign::MEDIA_COLLECTION),
                    ])
            ]);
    }

    protected function getActions(): array
    {
        $record = $this->appOrder;
        $admins = User::query()->role('super_admin')->get();
        $status = match ($record->status) {
            AppDevOrder::PENDING => 'Draft',
            AppDevOrder::IN_PROGRESS => 'In Progress',
            AppDevOrder::COMPLETED => 'Completed',
            AppDevOrder::REJECTED => 'Rejected',
            AppDevOrder::WITHDREW => 'Withdrew',
        };

        return [
            Action::make("Status: $status")
                ->color(fn() => match ($record->status) {
                    AppDevOrder::PENDING => 'secondary',
                    AppDevOrder::IN_PROGRESS => 'info',
                    AppDevOrder::COMPLETED => 'success',
                    AppDevOrder::REJECTED, AppDevOrder::WITHDREW => 'danger',
                })->disabled(),

            Action::make('Submit for Approval')
                ->visible(fn() => $record->status === AppDevOrder::PENDING)
                ->icon('heroicon-o-check-circle')
                ->color('primary')
                ->requiresConfirmation()
                ->action(function () use ($admins) {
                    $data = $this->form->getState();

                    unset($data['user']); // remove user from data
                    $this->record->update([
                        'status' => AppDevOrder::IN_PROGRESS,
                        'started_at' => now(),
                        ...$data
                    ]);

                    Notification::make()
                        ->title('New App Development Order')
                        ->body('A new app development order has been submitted for approval.')
                        ->actions([
                            NotificationAction::make('View Order')
                                ->url(AdminAppDevOrderResource::getUrl('view', ['record' => $this->record]))
                        ])
                        ->sendToDatabase($admins);
                }),

            Action::make('Withdraw')
                ->visible($record->status === AppDevOrder::IN_PROGRESS)
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->color('warning')
                ->action(function () use ($record) {
                    $record->update([
                        'status' => AppDevOrder::WITHDREW
                    ]);

                    Notification::make()
                        ->title('App Development Order Withdrawn')
                        ->body('Your app development order has been withdrawn.')
                        ->sendToDatabase($record->user);
                })
        ];
    }

}
