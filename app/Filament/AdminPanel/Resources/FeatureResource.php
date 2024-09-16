<?php

namespace App\Filament\AdminPanel\Resources;

use App\Filament\AdminPanel\Resources\FeatureResource\Pages;
use App\Filament\AdminPanel\Resources\FeatureResource\RelationManagers;
use App\Filament\AdminPanel\Resources\PlanResource\RelationManagers\FeaturesRelationManager;
use App\Models\Plans\Feature;
use App\Models\Plans\Plan;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Plan and Subscriptions';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFeatureFormComponents()
            );
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getFeatureTableComponents())
            ->filters(self::getFilterFeature())
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
            RelationManagers\PlansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeatures::route('/'),
            'create' => Pages\CreateFeature::route('/create'),
            'edit' => Pages\EditFeature::route('/{record}/edit'),
        ];
    }

    /**
     * @return array
     */
    public static function getFeatureTableComponents(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->description(fn(Feature $record): string => $record->description)
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('code'),
            Tables\Columns\TextColumn::make('type')
                ->badge(fn(string $state): string => match ($state) {
                    'feature' => 'Feature',
                    'limit' => 'Limit',
                })
                ->color(fn(string $state): string => match ($state) {
                    'limit' => 'gray',
                    'feature' => 'success',
                })
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('limit')
                ->searchable()
                ->sortable(),
            // word show in capital format
        ];
    }

    /**
     * @return array
     */
    public static function getFeatureFormComponents(): array
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
            Forms\Components\TextInput::make('code')
                ->autofocus()
                ->required()
                ->placeholder('Code'),
            Select::make('type')
                ->options([
                    'feature' => 'Feature',
                    'limit' => 'Limit',
                ])
                ->live()
                ->native(false),

            TextInput::make('limit')
                ->numeric()
                ->maxValue(30)
                ->placeholder('Limit')
                ->visible(fn(Get $get) => $get('type') == 'limit'),
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getFilterFeature(): array
    {
        return [
            SelectFilter::make('name')->label('Name')
                ->options(function () {
                    return Feature::get()->groupBy('name')
                        ->map(function ($item, $key) {
                            return $key;
                        });
                }),
            SelectFilter::make('type')->label('Type')
                ->options(function () {
                    return Feature::get()->groupBy('type')
                        ->map(function ($item, $key) {
                            return $key;
                        });
                }),
            SelectFilter::make('description')->label('Description')
                ->options(function () {
                    return Feature::get()->groupBy('description')
                        ->map(function ($item, $key) {
                            return $key;
                        });
                }),

        ];
    }
}
