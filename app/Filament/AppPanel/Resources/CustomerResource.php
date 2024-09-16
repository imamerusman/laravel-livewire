<?php

namespace App\Filament\AppPanel\Resources;

use App\Filament\AppPanel\Resources\CustomerResource\Pages\ListCustomers;
use App\Models\Customer;
use Exception;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $slug = 'customers';
    protected static ?string $recordTitleAttribute = 'email';

  /*  protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Customers';*/
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user-group';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('device_id')
                    ->limit(20)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('timezone')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->sortable()
                    ->since(),
            ])->filters([
                SelectFilter::make('timezone')
                    ->searchable()
                    ->options(auth()->user()->customers()->pluck('timezone', 'timezone')->toArray())
            ])->actions([
//                ViewAction::make('view')
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('user.name'),
                TextEntry::make('created_at')->since(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
//            'view' => ViewCustomer::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['email', 'user.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->user) {
            $details['User'] = $record->user->name;
        }

        return $details;
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['user']);
    }

    /*public static function can(string $action, ?Model $record = null): bool
    {
        return !auth()->user()->is_admin;
    }*/
}
