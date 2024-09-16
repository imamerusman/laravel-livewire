<?php

namespace App\Filament\AdminPanel\Resources;

use App\Filament\AdminPanel\Resources\AppDevOrderResource\Pages\CreateAppDevOrder;
use App\Filament\AdminPanel\Resources\AppDevOrderResource\Pages\EditAppDevOrder;
use App\Filament\AdminPanel\Resources\AppDevOrderResource\Pages\ListAppDevOrders;
use App\Filament\AdminPanel\Resources\AppDevOrderResource\Pages\ViewAppDevOrder;
use App\Models\AppDevOrder;
use App\Models\UserAppPreference;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class AppDevOrderResource extends Resource
{
    protected static ?string $model = AppDevOrder::class;

    protected static ?string $slug = 'app-dev-orders';

    protected static ?string $icon = 'heroicon-o-document-text';

    protected static ?string $label = 'App Dev Orders';

    protected static ?string $navigationGroup = 'App Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name'),

            TextInput::make('description'),

            Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

            TextInput::make('status')
                ->required(),

            DatePicker::make('started_at')
                ->label('Started Date'),

            DatePicker::make('completed_at')
                ->label('Completed Date'),

            TextInput::make('feedback'),

            Placeholder::make('created_at')
                ->label('Created Date')
                ->content(fn(?AppDevOrder $record): string => $record?->created_at?->diffForHumans() ?? '-'),

            Placeholder::make('updated_at')
                ->label('Last Modified Date')
                ->content(fn(?AppDevOrder $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

            Checkbox::make('registerMediaConversionsUsingModelInstance'),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema([
                TextEntry::make('name')
                    ->columnSpan(8),
                TextEntry::make('status')
                    ->columnSpan(4)
                    ->badge()
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            AppDevOrder::PENDING => 'Received',
                            AppDevOrder::IN_PROGRESS => 'In Progress',
                            AppDevOrder::COMPLETED => 'Completed',
                            AppDevOrder::REJECTED => 'Rejected',
                            AppDevOrder::WITHDREW => 'Withdrew',
                        };
                    })
                    ->color(fn(string $state): string => match ($state) {
                        AppDevOrder::PENDING => 'secondary',
                        AppDevOrder::IN_PROGRESS => 'warning',
                        AppDevOrder::COMPLETED => 'success',
                        AppDevOrder::REJECTED, AppDevOrder::WITHDREW => 'danger',
                    }),
                TextEntry::make('description')
                    ->markdown()
                    ->columnSpanFull(),
                Section::make('App Preference')
                    ->columnSpanFull()
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('user.appPreference.media')
                            ->label('Logo')
                            ->collection(UserAppPreference::MEDIA_COLLECTION),
                        SpatieMediaLibraryImageEntry::make('media')
                            ->label('Attached Files')
                            ->collection(AppDevOrder::MEDIA_COLLECTION),
                        TextEntry::make('user.appPreference.color')
                            ->label('App Primary Color')
                            ->copyable()
                            ->formatStateUsing(function (AppDevOrder $record) {
                                $color = $record->user?->appPreference?->color;
                                return new HtmlString("<span style='background: $color' class='inline-block w-3 h-3 rounded-full'></span>$color");
                            })
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->whereNot('status', AppDevOrder::PENDING))
            ->actions([
                ViewAction::make()
            ])
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->limit(30)
                    ->sortable(),

                TextColumn::make('user.name')
                    ->url(fn(AppDevOrder $record): string => UserResource::getUrl('view', ['record' => $record->user]))
                    ->searchable()
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

                TextColumn::make('started_at')
                    ->label('Started Date')
                    ->date()
                    ->since(),

                TextColumn::make('completed_at')
                    ->label('Completed Date')
                    ->date()
                    ->since(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAppDevOrders::route('/'),
            'create' => CreateAppDevOrder::route('/create'),
            'edit' => EditAppDevOrder::route('/{record}/edit'),
            'view' => ViewAppDevOrder::route('/{record}'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'user.name'];
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
