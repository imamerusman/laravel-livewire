<?php

namespace App\Http\Controllers;

use App\Http\Requests\YouTubeCallbackRequest;
use App\Services\YouTubeOAuthService;
use Exception;
use Illuminate\Support\Facades\Redis;

class Youtube0AuthController extends Controller
{
    protected YouTubeOAuthService $youtubeOAuthService;

    public function __construct(YouTubeOAuthService $youtubeOAuthService)
    {
        $this->youtubeOAuthService = $youtubeOAuthService;
    }

    public function redirectToYouTube()
    {
        $authUrl = $this->youtubeOAuthService->getAuthUrl();
        return redirect()->to($authUrl);
    }

    /**
     * @throws Exception
     */
    public function handleYoutubeCallback(YouTubeCallbackRequest $request)
    {
        $code = $request->input('code');

        $token = $this->youtubeOAuthService->exchangeCodeForToken($code);

        $redis = Redis::connection()->client();
        if (array_key_exists('refresh_token', $token) && array_key_exists('access_token', $token)) {
            $redis->set('youtube_access_token', json_encode($token));
        } else {
            throw new Exception(@$token['error'] . ' | ' . @$token['error_description']);
        }

        return redirect()->to('app')->with('success', 'You are now authenticated with YouTube.');
    }
}
