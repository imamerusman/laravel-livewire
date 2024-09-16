<?php

namespace App\Http\Resources\Api;

use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin WishList
 */
class WishListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'items' => $this->items->toArray(),
        ];
    }
}
