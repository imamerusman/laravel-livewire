<?php

namespace App\Filament\AdminPanel\Resources\UserResource\RelationManagers;

use App\Filament\AdminPanel\Resources\FigmaDesignResource;
use App\Models\FigmaDesign;
use App\Models\UserAppPreference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AppPreferencesRelationManager extends RelationManager
{
    protected static string $relationship = 'appPreference';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->recordTitleAttribute('App Preferences')
            ->columns(array(
                TextColumn::make('is_completed')
                    ->label('On Boarding Completed')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Yes' => 'success',
                        'No' => 'warning',
                    })
                    ->state(function (UserAppPreference $record) {
                        return $record->is_completed ? 'Yes' : 'No';
                    }),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('figmaDesign.media')
                    ->collection(FigmaDesign::MEDIA_COLLECTION)
                    ->tooltip(function (?UserAppPreference $record): ?string {
                        return $record->figmaDesign->name ?? null;
                    })
                    ->url(fn(?UserAppPreference $record) => FigmaDesignResource::getUrl('edit', ['record' => $record->theme]))
                    ->label('Selected Theme'),
//                Tables\Columns\SpatieMediaLibraryImageColumn::make('media')
//                    ->collection(AppReviewResource::MEDIA_COLLECTION)
//                    ->label('Selected Logo')
                TextColumn::make('color')
                    ->label('Selected Color')
                    ->copyable(),
            ))
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
