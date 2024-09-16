<?php

namespace App\Jobs\LiveStreams\ApiVideo;

use ApiVideo\Client\Model\LiveStreamUpdatePayload;
use App\Models\LiveStream;
use App\Services\ApiVideo;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        if (blank($this->liveStream->stream_id)) {
            Log::error('Stream does not have a stream_id', ['stream' => $this->liveStream]);
            return;
        }
        $client = (new ApiVideo())->getClient();
        $payload = new LiveStreamUpdatePayload();
        $payload->setName($this->liveStream->title);
        $createdStream = $client->liveStreams()->update(
            $this->liveStream->stream_id,
            $payload
        );

        if (!filled($createdStream)) {
            Log::error('Failed to update stream', ['stream' => $this->liveStream]);
        }
        $assets = $createdStream->getAssets();
        $this->liveStream->updateQuietly([
            'stream_link' => $assets->getPlayer(),
            'stream_url' => $assets->getHls(),
            'stream_key' => $createdStream->getStreamKey(),
            'stream_id' => $createdStream->getLiveStreamId(),
        ]);
    }

}
