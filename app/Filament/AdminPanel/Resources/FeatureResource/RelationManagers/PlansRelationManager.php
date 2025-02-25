<?php

namespace App\Filament\AdminPanel\Resources\FeatureResource\RelationManagers;

use App\Filament\AdminPanel\Resources\PlanResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlansRelationManager extends RelationManager
{
    protected static string $relationship = 'plans';

    public function form(Form $form): Form
    {
        return $form
            ->schema(PlanResource::getPlanFormComponent());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(PlanResource::getPlanTableComponent())
            ->filters([
                //
            ])
            ->headerActions([
                /*Tables\Actions\CreateAction::make(),*/
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->actions([
                /*Tables\Actions\EditAction::make(),*/
                Tables\Actions\DetachAction::make(),
                /*Tables\Actions\DeleteAction::make(),*/
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make(),
            ]);
    }
}
