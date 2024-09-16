<?php

namespace App\Http\Resources\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
/**
 * @mixin Blog
 * */
class BlogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug'=> $this->slug,
            'content' => $this->content,
            'published_at' => Carbon::parse($this->published_at)->format('d M Y'),
            'created_at' => $this->created_at->diffForHumans(),
            'tags' => $this->tags->pluck('name'),
            'image'=> $this->getFirstMediaUrl(Blog::MEDIA_COLLECTION),




        ];
    }
}
