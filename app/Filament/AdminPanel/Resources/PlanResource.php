<?php

namespace App\Filament\AdminPanel\Resources;

use App\Filament\AdminPanel\Resources\PlanResource\Pages;
use App\Filament\AdminPanel\Resources\PlanResource\RelationManagers;
use App\Filament\AdminPanel\Resources\PlanResource\RelationManagers\FeaturesRelationManager;
use App\Models\Plans\Feature;
use App\Models\Plans\Plan;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Plan and Subscriptions';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getPlanFormComponent());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getPlanTableComponent())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            FeaturesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }

    /**
     * @return array
     */
    public static function getPlanFormComponent(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->autofocus()
                ->required()
                ->placeholder('Name'),
            Forms\Components\TextInput::make('description')
                ->autofocus()
                ->required()
                ->placeholder('Description'),
            Forms\Components\TextInput::make('price')
                ->numeric()
                ->autofocus()
                ->required()
                ->placeholder('Price'),
            TextInput::make('duration')
                ->numeric()
                ->placeholder('Duration'),
        ];
    }

    /**
     * @return array
     */
    public static function getPlanTableComponent(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->description(fn(Plan $record): string => $record->description)
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('price')
                ->numeric(
                    decimalPlaces: 0,
                    decimalSeparator: '.',
                    thousandsSeparator: ',',
                ),
            Tables\Columns\TextColumn::make('currency')
                ->formatStateUsing(fn(string $state): string => strtoupper($state))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('duration')
                ->searchable()
                ->sortable(),
            // word show in capital format

            Tables\Columns\TextColumn::make('features_count')
                ->counts('features')
                ->badge()
        ];
    }
}
