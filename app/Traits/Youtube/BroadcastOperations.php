<?php

namespace App\Traits\Youtube;

use App\Models\LiveStream as LiveStreamModel;
use Carbon\Carbon;
use DateTimeInterface;
use Exception;
use Faker\Factory;
use Google\Service\YouTube\LiveBroadcast;
use Google\Service\YouTube\LiveBroadcastListResponse;
use Google\Service\YouTube\LiveBroadcastStatus;
use Google\Service\YouTube\LiveStream;
use Google_Service_YouTube;
use Google_Service_YouTube_CdnSettings;
use Google_Service_YouTube_IngestionInfo;
use Google_Service_YouTube_LiveBroadcast;
use Google_Service_YouTube_LiveBroadcastContentDetails;
use Google_Service_YouTube_LiveBroadcastSnippet;
use Google_Service_YouTube_LiveBroadcastStatus;
use Google_Service_YouTube_LiveStream;
use Google_Service_YouTube_LiveStreamContentDetails;
use Google_Service_YouTube_LiveStreamSnippet;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\String\s;

trait BroadcastOperations
{

    public function getListOfBroadCasts(): LiveBroadcastListResponse
    {
        $youtube = new Google_Service_YouTube($this->client);

        return $youtube->liveBroadcasts->listLiveBroadcasts('snippet', array(
            'broadcastType' => 'all', // You can filter by broadcast type (all, event, persistent)
            'maxResults' => 50, // Number of results per page (adjust as needed)
            'mine' => true, // Add this line to filter by broadcasts owned by the authenticated user
        ));
    }

    /**
     * @throws Exception
     */
    public function transitionBroadCast(LiveStreamModel $stream, BroadcastStatus $transitionType = BroadcastStatus::TESTING): void
    {
        $youtube = new Google_Service_YouTube($this->client);
        $broadcast = $this->fetchExistingBroadcast($stream->broadcast_id);
        $broadcastResponse = $youtube->liveBroadcasts->transition($transitionType->value, $broadcast->getId(), 'snippet,status,contentDetails');
        $stream->update([
            'status' => $transitionType->value,
            'start_time' => Carbon::parse($broadcastResponse->getSnippet()->getScheduledStartTime()),
            'end_time' => Carbon::parse($broadcastResponse->getSnippet()->getScheduledEndTime()),
        ]);
    }

    public function createNewLiveStream(
        string $title = null,
        string $description = null,
        bool   $madeForKids = true,
        string $scheduledStartTime = null,
        int $userId = null,
        string $resolution = '1080p',
        string $frameRate = '30fps',
        string $ingestionType = 'rtmp'
    ): array
    {
        // Define validation rules
        $youtube = new Google_Service_YouTube($this->client);

        // Create a new live stream object and set the snippet
        $stream = new Google_Service_YouTube_LiveStream();
        // Create a new live stream snippet with the necessary details
        $streamSnippet = new Google_Service_YouTube_LiveStreamSnippet();
        $userId = $userId ?? auth()->id();
        $streamTitle = ($title ?? $this->generateDynamicTitle()) . " [User: $userId]";
        $streamSnippet->setTitle($streamTitle);
        $streamSnippet->setDescription($description ?? $this->generateDynamicDescription());

        $stream->setSnippet($streamSnippet);

        // Create CDN settings
        $cdnSetting = new Google_Service_YouTube_CdnSettings();
        $cdnSetting->setResolution($resolution);
        $cdnSetting->setFrameRate($frameRate);

        // Create ingestion info
        $ingestionInfo = new Google_Service_YouTube_IngestionInfo();
        $ingestionInfo->setIngestionAddress('rtmp://your-ingestion-server-url');
        $cdnSetting->setIngestionInfo($ingestionInfo);
        $cdnSetting->setIngestionType($ingestionType);

        // Set CDN settings for the stream
        $stream->setCdn($cdnSetting);

        // Specify the parts for the API request
        $streamParts = 'snippet,cdn';

        // Make the API request to insert the live stream
        $streamInsertResponse = $youtube->liveStreams->insert($streamParts, $stream);

        // Create a new live broadcast snippet
        $broadcastSnippet = new Google_Service_YouTube_LiveBroadcastSnippet();
        $broadcastSnippet->setTitle($title ?? $this->generateDynamicTitle());
        $broadcastSnippet->setDescription($description ?? 'This is a live stream test.');

        if (!empty($scheduledStartTime)) {
            $broadcastSnippet->setScheduledStartTime($scheduledStartTime);
        } else {
            $broadcastSnippet->setScheduledStartTime(now());
        }

        // Create a new live broadcast object and set the snippet
        $broadcast = new Google_Service_YouTube_LiveBroadcast();
        $broadcast->setSnippet($broadcastSnippet);

        // Set the broadcast status
        $broadcastStatus = new Google_Service_YouTube_LiveBroadcastStatus();
        $broadcastStatus->setPrivacyStatus('public');
        $broadcastStatus->setSelfDeclaredMadeForKids($madeForKids);
        $broadcast->setStatus($broadcastStatus);

        // Specify the parts for the API request
        $broadcastParts = 'snippet,status';

        // Make the API request to insert the live broadcast
        $broadcastInsertResponse = $youtube->liveBroadcasts->insert($broadcastParts, $broadcast);

        // Bind the broadcast to the stream
        $youtube->liveBroadcasts->bind(
            $broadcastInsertResponse->id,
            'id,contentDetails',
            [
                'streamId' => $streamInsertResponse->id,
            ]
        );
        return [$broadcastInsertResponse,$streamInsertResponse];
    }

    /**
     * @throws Exception
     */
    public function getBroadcastsWithStreamDetails(): array
    {
        // Define validation rules
        $youtube = new Google_Service_YouTube($this->client);

        try {
            // List your own live broadcasts
            $queryParams = [
                'maxResults' => 50,
                'mine' => true
            ];
            $broadcasts = $youtube->liveBroadcasts->listLiveBroadcasts('snippet,contentDetails', $queryParams);

            $broadcastDetails = [];
            // Loop through the broadcasts
            foreach ($broadcasts->getItems() as $broadcast) {
                // Retrieve the live stream ID associated with the broadcast
                $streamId = $broadcast->getContentDetails()->getBoundStreamId();
                $service = new Google_Service_YouTube($this->client);

                $stream = null;
                if(filled($streamId)) {
                    $queryParams = [
                        'id' => $streamId
                    ];

                    $streams = collect($service->liveStreams->listLiveStreams('snippet,cdn', $queryParams)->getItems());
                    /** @var LiveStream $stream */
                    $stream = $streams->first();

                    $title = $stream->getSnippet()->getTitle();
                    $userId = auth()->user()->id;
                    if (!str_contains($title, "[User: $userId]")) {
                        //if This stream is not associated with the logged-in user then skip it
                        continue;
                    }
                }
                // Extract the details for the broadcast and the associated live stream
                $broadcastDetails[] = [
                    'broadcast' => [
                        'id' => $broadcast->getId(), // This will be used to update the broadcast
                        'title' => $broadcast->getSnippet()->getTitle(),
                        'description' => $broadcast->getSnippet()->getDescription(),
                        'scheduled_start_time' => $broadcast->getSnippet()->getScheduledStartTime(),
                    ],
                    'live_stream' => [
                        'stream_key' => filled($stream) ? $stream->getCdn()->getIngestionInfo()->getStreamName() : null,
                    ],
                ];
            }

            return $broadcastDetails;
        } catch (\Exception $e) {
            $message = 'Error getting broadcasts: ' . json_decode($e->getMessage(), true)['error']['message'];
            throw new Exception($message);
        }
    }

    /**
     * @throws Exception
     */
    public function updateBroadcast(
        string             $broadcastId,
        ?string            $title = null,
        ?string            $description = null,
        ?DateTimeInterface $scheduledStartTime = null,
        bool               $madeForKids = true,
        string             $privacyStatusForVideo = 'public'
    ): LiveBroadcast
    {
        // Define validation rules
        $youtube = new Google_Service_YouTube($this->client);

        // Fetch the existing broadcast
        $existingBroadcast = $youtube->liveBroadcasts->listLiveBroadcasts('snippet', ['id' => $broadcastId]);

        // Check if the broadcast exists
        if (count($existingBroadcast) === 0) {
            throw new Exception('Broadcast not found.');
        }

        // Update the existing broadcast with the new details
        $broadcast = $existingBroadcast[0];
        $broadcast->getSnippet()->setTitle($title);
        $broadcast->getSnippet()->setDescription($description);
        $broadcast->getSnippet()->setScheduledStartTime(filled($scheduledStartTime)
            ? Carbon::parse($scheduledStartTime)->toIso8601String()
            : now()->toIso8601String());
        $broadcast->getStatus()?->setPrivacyStatus($privacyStatusForVideo);
        $broadcast->getStatus()?->setMadeForKids($madeForKids);

        return $youtube->liveBroadcasts->update('snippet,status', $broadcast);
    }

    /**
     * @throws Exception
     */
    public function deleteBroadcast(string $broadcastId): void
    {
        $youtube = new Google_Service_YouTube($this->client);
        $existingBroadcast = $this->fetchExistingBroadcast($broadcastId);

        // Get the associated stream ID
        $streamId = $existingBroadcast->getContentDetails()->getBoundStreamId();

        // Delete the broadcast
        $youtube->liveBroadcasts->delete($broadcastId);

        // Delete the stream
        $youtube->liveStreams->delete($streamId);
    }


    public function generateDynamicTitle(): string
    {
        $faker = Factory::create();
        return $faker->sentence;
    }

    public function generateDynamicDescription(): string
    {
        $faker = Factory::create();
        return $faker->paragraph;
    }

    /**
     * @throws Exception
     */
    public function fetchExistingBroadcast(string $broadcastId): LiveBroadcast
    {
        $youtube = new Google_Service_YouTube($this->client);
        $existingBroadcastList = $youtube->liveBroadcasts->listLiveBroadcasts('snippet,contentDetails', ['id' => $broadcastId]);
        $existingBroadcasts = $existingBroadcastList->getItems();

        // Check if the broadcast exists
        if (empty($existingBroadcasts)) {
            throw new Exception('Broadcast not found.');
        }
        return collect($existingBroadcasts)->first();
    }
}
