<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Builder;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::query()
            ->orderBy('created_at', 'desc')
            ->published()
            ->paginate(10);
        $blogs->load('tags','media');
        return view('frontend.blog.index', ['blogs' => $blogs]);
    }

    public function view(Blog $blog)
    {
        $media = $blog->getFirstMedia(Blog::MEDIA_COLLECTION);
        $relatedBlogs = Blog::query()
            ->withWhereHas('tags', function (Builder $query) use ($blog) {
                $query->whereJsonContains('name', $blog->tags->pluck('name')->toArray());
            })
            ->where('id', '!=', $blog->id)
            ->published()
            ->limit(3)
            ->get();
        return view('frontend.blog.detail', ['blog' => $blog, 'media' => $media, 'relatedBlogs' => $relatedBlogs]);
    }
}
