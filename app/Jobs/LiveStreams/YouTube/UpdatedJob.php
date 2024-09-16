<?php

namespace App\Jobs\LiveStreams\YouTube;

use App\Models\LiveStream;
use App\Services\YouTubeOAuthService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly LiveStream $liveStream)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $service = new YouTubeOAuthService();

        $service->updateBroadcast(
            broadcastId: $this->liveStream->broadcast_id,
            title: $this->liveStream->title,
            description: $this->liveStream->description,
            scheduledStartTime: $this->liveStream->start_time,
            madeForKids: $this->liveStream->made_for_kids,
        );
    }

}
