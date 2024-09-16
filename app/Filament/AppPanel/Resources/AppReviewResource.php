<?php

namespace App\Filament\AppPanel\Resources;

use App\Filament\AppPanel\Resources\AppReviewResource\Pages\CreateAppReview;
use App\Filament\AppPanel\Resources\AppReviewResource\Pages\EditAppReview;
use App\Filament\AppPanel\Resources\AppReviewResource\Pages\ListAppReviews;
use App\Filament\AppPanel\Resources\AppReviewResource\Pages\ViewAppReview;
use App\Models\AppReview;
use App\Models\Plans\PlanSubscription;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class AppReviewResource extends Resource
{
    protected static ?string $model = AppReview::class;

    protected static ?string $navigationGroup = 'App Management';

    protected static ?string $slug = 'app-reviews';
    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationBadge(): ?string
    {
        return auth()->user()->appReviews()->count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                TextInput::make('title')
                    ->placeholder('First Revisions')
                    ->required(),

                MarkdownEditor::make('description')
                    ->placeholder('Describe your app here.')
                    ->required(),

                SpatieMediaLibraryFileUpload::make('media')
                    ->label('Files')
                    ->imageEditor()
                    ->multiple()
                    ->collection(AppReview::MEDIA_COLLECTION)
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        /** @var PlanSubscription $subscription */
        $subscription = auth()->user()->activeSubscription();
        $appRevisionFeatureLimit = $subscription === false ? 0 : $subscription?->getRemainingOf('revision');

        return $table
            ->heading(new HtmlString(<<<HTML
                <div class="flex items-center space-x-2">
                    <div class="flex-1">You have
                    <span class="font-semibold underline">$appRevisionFeatureLimit</span> app revision(s) left.
                </div>
            HTML
            ))
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->actions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(function (AppReview $appReview) {
                        return $appReview->status === 'pending';
                    }),
                Action::make('Withdraw')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('If you want to withdraw your app review, please provide a feedback.')
                    ->form(function (Form $form) {
                        return $form->schema([
                            MarkdownEditor::make('feedback')
                                ->required()
                        ]);
                    })
                    ->visible(function (AppReview $appReview) {
                        return $appReview->status === AppReview::PENDING;
                    })
                    ->hidden(function (AppReview $appReview) {
                        return $appReview->status === AppReview::WITHDREW;
                    })
                    ->action(function (array $data, AppReview $appReview) {
                        /** @var PlanSubscription $sub */
                        $sub = auth()->user()->activeSubscription();
                        $sub->unconsumeFeature('revision', 1);
                        $appReview->update([
                            'feedback' => $data['feedback'] ?? '',
                            'status' => 'withdrew'
                        ]);
                    })
            ])
            ->columns([
                TextColumn::make('title')
                    ->sortable(),


                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'pending' => 'Received',
                            'in_progress' => 'In Progress',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                            'withdrew' => 'Withdrew',
                        };
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'in_progress' => 'warning',
                        'approved' => 'success',
                        'rejected', 'withdrew' => 'danger',
                    }),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('title')
                ->placeholder('First Revisions'),

            TextEntry::make('status')
                ->badge()
                ->formatStateUsing(function (string $state): string {
                    return match ($state) {
                        'pending' => 'Received',
                        'in_progress' => 'In Progress',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'withdrew' => 'Withdrew',
                    };
                })
                ->color(fn(string $state): string => match ($state) {
                    'pending' => 'gray',
                    'in_progress' => 'warning',
                    'approved' => 'success',
                    'rejected', 'withdrew' => 'danger',
                }),

            \Filament\Infolists\Components\Section::make('Description')
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextEntry::make('description')
                        ->hiddenLabel()
                        ->markdown()
                        ->columnSpan(4)
                ]),

            \Filament\Infolists\Components\Section::make('Feedback')
                ->schema([
                TextEntry::make('feedback')
                    ->hiddenLabel()
                    ->markdown()
                    ->columnSpan(4)
            ])->visible(fn(AppReview $appReview) => filled($appReview->feedback))
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAppReviews::route('/'),
            'create' => CreateAppReview::route('/create'),
            'edit' => EditAppReview::route('/{record}/edit'),
            'view' => ViewAppReview::route('/{record}')
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }
}
