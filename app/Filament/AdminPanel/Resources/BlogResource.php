<?php

namespace App\Filament\AdminPanel\Resources;

use App\Filament\AdminPanel\Resources\BlogResource\Pages\{CreateBlog, EditBlog, ListBlogs, ViewBlog};
use App\Models\Blog;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\{DatePicker,
    MarkdownEditor,
    Placeholder,
    SpatieMediaLibraryFileUpload,
    SpatieTagsInput,
    TextInput};
use Filament\Forms\Form;
use Filament\Infolists\Components\{Group, ImageEntry, Section, Split, TextEntry};
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 0;

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')
                    ->collection(Blog::MEDIA_COLLECTION)
                    ->label('Image'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(20)
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->limit(20)
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->getStateUsing(fn(Blog $record): string => $record->published_at?->isPast() ? 'Published' : 'Draft')
                    ->badge()
                    ->colors([
                        'success' => 'Published',
                    ]),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published Date')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\Filter::make('published_at')
                    ->form([
                        DatePicker::make('published_from')
                            ->placeholder(fn($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        DatePicker::make('published_until')
                            ->placeholder(fn($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                            )
                            ->when(
                                $data['published_until'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['published_from'] ?? null) {
                            $indicators['published_from'] = 'Published from ' . Carbon::parse($data['published_from'])->toFormattedDateString();
                        }
                        if ($data['published_until'] ?? null) {
                            $indicators['published_until'] = 'Published until ' . Carbon::parse($data['published_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function () {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(Blog::class, 'slug', ignoreRecord: true),

                                MarkdownEditor::make('content')
                                    ->required()
                                    ->columnSpan('full'),

                                DatePicker::make('published_at')
                                    ->label('Published Date'),

                                SpatieTagsInput::make('tags'),
                            ]),

                        Forms\Components\Section::make('Image')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->collection(Blog::MEDIA_COLLECTION)
                                    ->label('Image')
                                    ->image()
                                    ->hiddenLabel(),
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => fn(?Blog $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn(Blog $record): ?string => $record->created_at?->diffForHumans()),

                        Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn(Blog $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn(?Blog $record) => $record === null),
            ])
            ->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Split::make([

                            Group::make([
                                TextEntry::make('title'),
                                TextEntry::make('slug'),
                                TextEntry::make('published_at')
                                    ->badge()
                                    ->date()
                                    ->color('success'),
                            ]),
                            Group::make([
                                TextEntry::make('tags.name')
                                    ->badge(),
                            ]),

                            ImageEntry::make('image')
                                ->hiddenLabel()
                                ->grow(false),
                        ])->from('lg'),
                    ]),
                Section::make('Content')
                    ->schema([
                        TextEntry::make('content')
                            ->prose()
                            ->markdown()
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlogs::route('/'),
            'create' => CreateBlog::route('/create'),
            'edit' => EditBlog::route('/{record}/edit'),
            'view' => ViewBlog::route('/{record}'),
        ];
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }
}
