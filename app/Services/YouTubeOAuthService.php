<?php

namespace App\Services;

use App\Traits\Youtube\BroadcastOperations;
use Google_Client;
use Illuminate\Support\Facades\Redis;

class YouTubeOAuthService
{
    use BroadcastOperations;

    protected Google_Client $client;

    public function __construct()
    {
        $redisClient = Redis::connection()->client();
        $this->client = new Google_Client();
        $this->client->setClientId(config('services.youtube.client_id'));
        $this->client->setClientSecret(config('services.youtube.client_secret'));
        $this->client->setRedirectUri(config('services.youtube.redirect_uri'));
        $this->client->addScope(config('services.youtube.scope'));
        $this->client->setAccessType('offline'); // Request a refresh token
        if ($redisClient->exists('youtube_access_token')) {
            $accessToken = json_decode($redisClient->get('youtube_access_token'), true);
            $this->client->setAccessToken($accessToken);
        }
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function exchangeCodeForToken($code): array
    {
        return $this->client->fetchAccessTokenWithAuthCode($code);
    }

    public function refreshToken($refreshToken): array
    {
        $this->client->setAccessToken(['refresh_token' => $refreshToken]);
        if ($this->client->isAccessTokenExpired()) {
            $this->client->fetchAccessTokenWithRefreshToken();
        }
        return $this->client->getAccessToken();
    }

    public function getClient(): Google_Client
    {
        return $this->client;
    }
}
