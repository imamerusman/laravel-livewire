<?php

namespace App\Filament\AppPanel\Resources\LiveStreamResource\Pages;

use App\Filament\AppPanel\Resources\LiveStreamResource;
use App\Models\LiveStream;
use App\Services\YouTubeOAuthService;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class CreateLiveStream extends CreateRecord
{
    protected static string $resource = LiveStreamResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id;
        return $data;
    }

    public function getRedirectUrl(): string
    {
        /** @var Resource $resource */
        $resource = $this->getResource();
        return $resource::getURL();
    }
}
