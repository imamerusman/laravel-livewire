<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Http\Resources\Api\BannerResource;
use App\Http\Resources\Api\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class BlogController extends Controller
{
    public function allBlogs()
    {
        return $this->success(BlogResource::collection(Blog::with('tags','media')->get()));
    }
}
