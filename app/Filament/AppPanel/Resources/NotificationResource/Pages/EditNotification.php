<?php

namespace App\Filament\AppPanel\Resources\NotificationResource\Pages;

use App\Filament\AppPanel\Resources\NotificationResource;
use App\Models\Notifications\OtherNotification;
use App\Models\Notifications\ScheduleNotification;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use JetBrains\PhpStorm\NoReturn;
use Laravel\Telescope\Storage\EntryModel;

class EditNotification extends EditRecord implements HasTable
{
    use InteractsWithTable;
    public ScheduleNotification |null $model = null;

    protected static string $resource = NotificationResource::class;
    protected static string $view = 'filament.app-panel.resources.notifications.pages.edit-notifications';

    public function table(Table $table): Table
    {
        return $table
            ->query(EntryModel::query()->whereType('job')->newQuery())
            ->columns([
                TextColumn::make('content.status'),
            ])->heading('Analytics');
    }

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
