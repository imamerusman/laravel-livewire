<?php

namespace App\Services;
use ApiVideo\Client\BaseClient;
use Symfony\Component\HttpClient\Psr18Client;
use ApiVideo\Client\Client;
class ApiVideo
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            baseUri: config('services.api_video.base_url'),
            apiKey: config('services.api_video.api_key'),
            httpClient: new Psr18Client()
        );
        return $this->client;
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
