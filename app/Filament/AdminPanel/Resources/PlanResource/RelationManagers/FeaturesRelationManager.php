<?php

namespace App\Filament\AdminPanel\Resources\PlanResource\RelationManagers;

use App\Filament\AdminPanel\Resources\FeatureResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeaturesRelationManager extends RelationManager
{
    protected static string $relationship = 'features';

    public function form(Form $form): Form
    {
        return $form
            ->schema(FeatureResource::getFeatureFormComponents());
    }

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(FeatureResource::getFeatureTableComponents())
            ->inverseRelationship('plan')
            ->filters(FeatureResource::getFilterFeature())
            ->headerActions([
                /*Tables\Actions\CreateAction::make(),*/
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
            /*    Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),*/
                Tables\Actions\DetachAction::make(),
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
}
