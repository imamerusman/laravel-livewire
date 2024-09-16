<?php

namespace App\Jobs\LiveStreams\ApiVideo;

use App\Services\ApiVideo;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly string $liveStreamId)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $client = (new ApiVideo())->getClient();

        $client->liveStreams()->delete(
            liveStreamId: $this->liveStreamId,
        );
    }

}
