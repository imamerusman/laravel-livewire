<?php

namespace App\Filament\AdminPanel\Resources;

use App\Filament\AdminPanel\Resources\AppReviewResource\Pages\ListAppReviews;
use App\Filament\AdminPanel\Resources\AppReviewResource\Pages\ViewAppReview;
use App\Models\AppReview;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AppReviewResource extends Resource
{
    protected static ?string $model = AppReview::class;

    protected static ?string $slug = 'app-reviews';
    protected static ?string $navigationGroup = 'App Management';
    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationBadge(): ?string
    {
        return AppReview::query()->where('status','pending')->count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

            TextInput::make('title')
                ->required(),

            TextInput::make('description')
                ->required(),

            TextInput::make('feedback'),

            TextInput::make('status')
                ->required(),

            Placeholder::make('created_at')
                ->label('Created Date')
                ->content(fn(?AppReview $record): string => $record?->created_at?->diffForHumans() ?? '-'),

            Placeholder::make('updated_at')
                ->label('Last Modified Date')
                ->content(fn(?AppReview $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

            Checkbox::make('registerMediaConversionsUsingModelInstance'),
        ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('title'),

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

            Section::make('Description')
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextEntry::make('description')
                        ->hiddenLabel()
                        ->markdown()
                        ->columnSpan(4)
                ]),

            Section::make('Feedback')
                ->schema([
                TextEntry::make('feedback')
                    ->hiddenLabel()
                    ->markdown()
                    ->columnSpan(4)
            ])->visible(fn(AppReview $appReview) => filled($appReview->feedback))
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->actions([
                ViewAction::make()
            ])
            ->columns([
            TextColumn::make('user.name')
                ->searchable()
                ->sortable(),

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

    public static function getPages(): array
    {
        return [
            'index' => ListAppReviews::route('/'),
            'view' => ViewAppReview::route('/{record}'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'user.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->user) {
            $details['User'] = $record->user->name;
        }

        return $details;
    }
}
