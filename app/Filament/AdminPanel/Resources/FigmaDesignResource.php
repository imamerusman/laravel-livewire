<?php

namespace App\Filament\AdminPanel\Resources;

use App\Filament\AdminPanel\Resources\FigmaDesignResource\Pages;
use App\Models\FigmaDesign;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;


use Filament\Forms\Components\{
    SpatieMediaLibraryFileUpload,
    };

class FigmaDesignResource extends Resource
{
    protected static ?string $model = FigmaDesign::class;

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Figma Menu';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Account')->schema([
                    Forms\Components\Grid::make()->columns()->schema([
                        TextInput::make('name')->required(),
                        Forms\Components\Select::make('figma_cat_id')
                            ->relationship('figma_cat', 'name')
                            ->required()
                            ->searchable()
                            ->placeholder('Select Category')
                            ->label('Select Category')->preload(),

                    ]),
                   /* Textarea::make('description')->required(),
                    Textarea::make('src')->required(),*/
                    SpatieMediaLibraryFileUpload::make('media')
                        ->collection(FigmaDesign::MEDIA_COLLECTION)
                        ->label('Image')
                        ->image()
                        ->hiddenLabel(),
                ]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //name
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                //image
//                Tables\Columns\ImageColumn::make('image'),
                SpatieMediaLibraryImageColumn::make('media')
                    ->collection(FigmaDesign::MEDIA_COLLECTION)
                    ->label('Image'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListFigmaDesigns::route('/'),
            'create' => Pages\CreateFigmaDesign::route('/create'),
            'edit' => Pages\EditFigmaDesign::route('/{record}/edit'),
        ];
    }
}
