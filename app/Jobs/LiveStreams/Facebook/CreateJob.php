<?php

namespace App\Jobs\LiveStreams\Facebook;

use ApiVideo\Client\Model\LiveStreamCreationPayload;
use App\Models\LiveStream;
use App\Services\ApiVideo;
use App\Services\Facebook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly LiveStream $liveStream)
    {
    }

    public function handle(): void
    {
        $client = new Facebook();
        $createdStream = $client->createStream(
            title: $this->liveStream->title,
            description: $this->liveStream->description
        );
        if (!filled($createdStream)) {
            Log::error('Failed to create stream', ['stream' => $this->liveStream]);
        }

        /*$this->liveStream->update([
            'stream_link' => $assets->getPlayer(),
            'stream_url' => $assets->getHls(),
            'stream_key' => $createdStream->getStreamKey(),
            'stream_id' => $createdStream->getLiveStreamId(),
        ]);*/
    }
}
