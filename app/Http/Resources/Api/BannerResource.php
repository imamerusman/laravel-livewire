<?php

namespace App\Http\Resources\Api;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Banner */
class BannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $this->loadMissing('media');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'link' => $this->link,
            'media' => $this->media_url,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
