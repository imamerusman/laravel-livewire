<?php

namespace App\Traits\Youtube;

/**
 * Acceptable values are:
 * complete – The broadcast is over. YouTube stops transmitting video.
 * live – The broadcast is visible to its audience. YouTube transmits video to the broadcast's monitor stream and its broadcast stream.
 * testing – Start testing the broadcast. YouTube transmits video to the broadcast's monitor stream. Note that you can only transition a broadcast to the testing state if its contentDetails.monitorStream.enableMonitorStream property is set to true.
 */
enum BroadcastStatus: string
{
    case COMPLETE = 'complete';
    case LIVE = 'live';
    case TESTING = 'testing';
}
