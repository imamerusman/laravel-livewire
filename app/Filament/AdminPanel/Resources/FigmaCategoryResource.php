<?php

namespace App\Filament\AdminPanel\Resources;

use App\Filament\AdminPanel\Resources\FigmaCategoryResource\Pages;
use App\Models\FigmaCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class FigmaCategoryResource extends Resource
{
    protected static ?string $model = FigmaCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Figma Menu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //name
                Forms\Components\TextInput::make('name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //name
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFigmaCategories::route('/'),
            'create' => Pages\CreateFigmaCategory::route('/create'),
            'edit' => Pages\EditFigmaCategory::route('/{record}/edit'),
        ];
    }
}
