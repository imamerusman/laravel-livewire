<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BannerResource;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function list(Request $request)
    {
        $user = $request->user();
        return $this->success(BannerResource::collection($user->banners));
    }
}
