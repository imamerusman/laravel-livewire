<?php

namespace App\Jobs\LiveStreams\ApiVideo;

use ApiVideo\Client\Model\LiveStreamCreationPayload;
use ApiVideo\Client\Model\RestreamsRequestObject;
use App\Models\LiveStream;
use App\Services\ApiVideo;
use App\Services\Facebook;
use Exception;
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

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $client = (new ApiVideo())->getClient();
        $payload = new LiveStreamCreationPayload();
        $payload->setName($this->liveStream->title);
        $payload->setRecord(true);

        /*$facebookStream = $this->createStreamFromFacebook();
        $payload->setRestreams([$facebookStream]);*/

        $createdStream = $client->liveStreams()->create($payload);

        if (!filled($createdStream)) {
            Log::error('Failed to create stream', ['stream' => $this->liveStream]);
        }
        $assets = $createdStream->getAssets();
        $this->liveStream->update([
            'stream_link' => $assets->getPlayer(),
            'stream_url' => $assets->getHls(),
            'stream_key' => $createdStream->getStreamKey(),
            'stream_id' => $createdStream->getLiveStreamId(),
        ]);
    }

    /**
     * @throws Exception
     */
    public function createStreamFromFacebook(): RestreamsRequestObject
    {
        $client = new Facebook();
        $createdStream = $client->createStream(
            title: $this->liveStream->title,
            description: $this->liveStream->description
        );
        if (!filled($createdStream)) {
            throw new Exception("Facebook stream failed to create.");
        }
        $reStream = new RestreamsRequestObject();

        $reStream->setName('Facebook');
        $reStream->setServerUrl($createdStream->stream_url);
        $reStream->setStreamKey($createdStream->stream_key);

        return $createdStream;
    }
}
