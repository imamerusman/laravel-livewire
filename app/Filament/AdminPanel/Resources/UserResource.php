<?php

namespace App\Filament\AdminPanel\Resources;

use App\Filament\AdminPanel\Resources\UserResource\Pages\CreateUser;
use App\Filament\AdminPanel\Resources\UserResource\Pages\EditUser;
use App\Filament\AdminPanel\Resources\UserResource\Pages\ListUsers;
use App\Filament\AdminPanel\Resources\UserResource\Pages\ViewUser;
use App\Filament\AdminPanel\Resources\UserResource\RelationManagers\AppPreferencesRelationManager;
use App\Models\User;
use Closure;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use STS\FilamentImpersonate\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    /**
     * @return string|null
     */
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user-group';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required(),
            TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('password')
                ->password()
                ->maxLength(255)
                ->dehydrateStateUsing(static function ($state) use ($form) {
                    if (!empty($state)) {
                        return Hash::make($state);
                    }

                    $user = User::find($form->getColumns())->first();
                    return $user?->password ?? null;
                }),
            Forms\Components\Select::make('roles')
                ->multiple()
                ->relationship('roles', 'name')
        ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')
                    ->formatStateUsing(self::getUserDisplayName())
                    ->description(function (User $record) {
                        return $record->email;
                    })
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y')->sortable()->since(),
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('unverified')
                    ->query(fn(Builder $query): Builder => $query->whereNull('email_verified_at')),
            ]);
        $table->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
//            Tables\Actions\DeleteAction::make(),
        ]);
        if (config('filament-user.impersonate')) {
            $table->prependActions([
                Impersonate::make('impersonate'),
            ]);
        }

        return $table;
    }

    /**
     * @return Closure
     */
    public static function getUserDisplayName(): Closure
    {
        return function (User $record) {
            $emailVerifiedAt = null;
            if (isset($record->email_verified_at)) {
                $emailVerifiedAt = "<svg style='--c-400:var(--success-400);--c-500:var(--success-500);'
                                                    class='fi-ta-icon-item fi-ta-icon-item-size-lg ml-2 h-6 w-6 fi-color-custom text-custom-500 dark:text-custom-400'
                                                    xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' aria-hidden='true'>
                                                  <path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path>
                                                </svg>";
            }
            $name = $record->name;
            return new HtmlString(
                "<span class='flex items-center space-x-2'>
                                    $name
                                    $emailVerifiedAt
                                  </span>"
            );
        };
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(12)
            ->schema([
                Section::make()
                    ->columns(8)
                    ->columnSpan(8)
                    ->schema([
                        TextEntry::make('name')
                            ->columnSpan(4)
                            ->tooltip(function (User $record): ?string {
                                if ($record->hasVerifiedEmail()) {
                                    return 'Email verified';
                                } else {
                                    return 'Email not verified';
                                }
                            })
                            ->formatStateUsing(self::getUserDisplayName()),
                        TextEntry::make('email')
                            ->columnSpan(4),

                        TextEntry::make('roles.name')
                            ->badge()
                            ->formatStateUsing(function (User $record) {
                                $roles = $record->roles->pluck('name')->toArray();
                                $roles = array_map(function ($role) {
                                    return ucfirst(str_replace('_', ' ', $role));
                                }, $roles);
                                return implode(', ', $roles);
                            })
                            ->columnSpan(4)
                    ]),

                Section::make()
                    ->columnSpan(4)
                    ->columns(4)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Joined:')
                            ->columnSpan(2)
                            ->since(),
                        TextEntry::make('updated_at')
                            ->label('Last Activity:')
                            ->columnSpan(2)
                            ->since(),
                        TextEntry::make('has_plan')
                            ->columnSpan(4)
                            ->label('Subscription:')
                            ->formatStateUsing(function (User $record) {
                                if ($record->activeSubscription()) {
                                    $plan = $record->activeSubscription();
                                    $url = PlanResource::getUrl('edit', ['record' => $plan]);
                                    return new HtmlString("
                                    <a wire:navigate href='$url' class='text-indigo-500'>
                                        $plan->display_name
                                    </a>");
                                }
                                return 'None';
                            }),
                        TextEntry::make('has_completed_app_preferences_steps')
                            ->columnSpan(4)
                            ->label('Onboarding:')
                            ->formatStateUsing(function (User $record) {
                                if ($record->hasCompletedAppPreferencesSteps()) {
                                    return 'Completed';
                                }
                                return 'Incomplete';
                            }),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
            'view' => ViewUser::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            AppPreferencesRelationManager::class,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return config('filament-user.group');
    }
}
