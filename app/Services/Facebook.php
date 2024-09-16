<?php

namespace App\Services;

use App\Traits\Facebook\BroadcastStatus;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Redis;

class Facebook
{
    public ?array $token = null;
    public string $baseUrl = 'https://graph.facebook.com/v3.3/';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $redis = Redis::connection()->client();
        $accessToken = $redis->get('facebook_access_token');
        if (!$accessToken) {
            throw new Exception('Facebook access token not found');
        }
        $this->token = json_decode($accessToken, true);
    }

    public function createStream(string $title, string $description)
    {
        $client = new Client();
        $params = http_build_query([
            'status' => BroadcastStatus::LIVE_NOW,
            'access_token' => $this->token->access_token,
            'title' => $title,
            'description' => $description,
        ]);
        $request = new Request('POST', $this->baseUrl . 'me/live_videos?' . $params);
        return $client->sendAsync($request)->wait();
    }
}
