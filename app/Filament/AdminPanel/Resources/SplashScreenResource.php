<?php

namespace App\Filament\AdminPanel\Resources;

use App\Filament\AdminPanel\Resources\SplashScreenResource\Pages;
use App\Filament\AdminPanel\Resources\SplashScreenResource\RelationManagers;
use App\Models\FigmaDesign;
use App\Models\SplashScreen;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SplashScreenResource extends Resource
{
    protected static ?string $model = SplashScreen::class;

    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Figma Menu';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('figma_design_id')
                    ->relationship('figmaDesign', 'name')
                    ->required(),
                SpatieMediaLibraryFileUpload::make('media')
                    ->collection(SplashScreen::MEDIA_COLLECTION)
                    ->label('Image')
                    ->image()
                    ->multiple()
                    ->hiddenLabel(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('figmaDesign.name')
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('media')
                    ->collection(SplashScreen::MEDIA_COLLECTION)
                    ->label('Image'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListSplashScreens::route('/'),
            'create' => Pages\CreateSplashScreen::route('/create'),
            'edit' => Pages\EditSplashScreen::route('/{record}/edit'),
        ];
    }
}
