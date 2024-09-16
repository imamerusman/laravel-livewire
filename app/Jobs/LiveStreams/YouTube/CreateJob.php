<?php

namespace App\Jobs\LiveStreams\YouTube;

use App\Models\LiveStream;
use App\Services\YouTubeOAuthService;
use Carbon\Carbon;
use Google\Service\YouTube\LiveBroadcast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly LiveStream $liveStream)
    {
    }

    public function handle(): void
    {
        $service = new YouTubeOAuthService();

        /** @var \Google\Service\YouTube\LiveStream $createdStream */
        /** @var LiveBroadcast $createdBroadcast */
        [$createdBroadcast, $createdStream] = $service->createNewLiveStream(
            title: $this->liveStream->title,
            description: $this->liveStream->description,
            madeForKids: $this->liveStream->made_for_kids,
            scheduledStartTime: Carbon::parse($this->liveStream->start_time),
            userId: $this->liveStream->user_id,
        );
        $this->liveStream->update([
            'stream_url' => filled($createdStream) ? $createdStream->getCdn()->getIngestionInfo()->getIngestionAddress() : null,
            'broadcast_id' => filled($createdBroadcast) ? $createdBroadcast->getId() : null,
            'stream_id' => filled($createdStream) ? $createdStream->getId() : null,
            'stream_key' => filled($createdStream) ? $createdStream->getCdn()->getIngestionInfo()->getStreamName() : null,
        ]);
    }
}
