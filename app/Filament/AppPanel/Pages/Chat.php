<?php

namespace App\Filament\AppPanel\Pages;

use App\Models\Conversation;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Chat extends Page implements HasTable, HasForms
{
    use
        HasPageShield,
        InteractsWithTable,
        InteractsWithForms;


    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static string $view = 'filament.app-panel.pages.chat';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Support';

    public ?Conversation $conversation = null;

    #[Rule(['required_if:attachment,null', 'string'])]
    public ?string $message = '';

    #[Rule(['nullable', 'array'])]
    public ?array $attachment = [];

    public bool $showAttachment = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(auth()->user()->conversations()->getQuery()->orderByDesc('last_message_at'))
            ->columns([
                TextColumn::make('recipient_user.name')
                    ->label('Recent Conversations')
                    ->color('warning')
                    ->icon('heroicon-o-user')
                    ->description(function (Conversation $record) {
                        $message = Str::limit($record->last_message ?? 'Start New Conversation', 30);
                        $isRead = $record->is_read ? 'font-normal' : 'font-bold';
                        $lastMessageAt = filled($record->last_message_at)
                            ? $record->last_message_at?->diffForHumans()
                            : $record->updated_at->diffForHumans();
                        return new HtmlString(
                            "<span class='$isRead'>
                                    $message - <em>$lastMessageAt</em>
                                </span>"
                        );
                    })
                    ->color('warning')
                    ->action(function (Conversation $record) {
                        $this->openThis(conversation: $record);
                    }),
            ])
            ->filters([
                // ...
            ])->actions([
                DeleteAction::make()->label('')
                    ->action(function (Conversation $record) {
                        $this->conversation = null;
                        $record->messages()->delete();
                        $record->delete();
                    })
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Attachment')->schema([
                SpatieMediaLibraryFileUpload::make('attachment')->hiddenLabel()
            ])
                ->visible($this->showAttachment)
                ->collapsible()
        ]);
    }
    private function openThis(Conversation $conversation): void
    {
        $conversation->markAsRead();
        $this->conversation = $conversation;
    }

    public function submit(): void
    {
        $this->validate();

        /** @var TemporaryUploadedFile $attachment */
        $attachment = collect($this->attachment)->first();

        auth()->user()->sendMessage(
            to: $this->conversation->recipient_user,
            content: is_null($attachment) ? $this->message : $attachment,
        );

        $this->dispatch('messageSent');
        $this->message = null;
        $this->attachment = [];
        $this->showAttachment = false;
    }
}
