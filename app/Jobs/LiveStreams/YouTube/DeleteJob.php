<?php

namespace App\Jobs\LiveStreams\YouTube;

use App\Services\YouTubeOAuthService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly string $broadcastId)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $service = new YouTubeOAuthService();

        $service->deleteBroadcast(
            broadcastId: $this->broadcastId,
        );
    }

}
