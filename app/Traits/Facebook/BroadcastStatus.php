<?php

namespace App\Traits\Facebook;

enum BroadcastStatus
{
    case UNPUBLISHED;
    case LIVE_NOW;
    case SCHEDULED_UNPUBLISHED;
    case SCHEDULED_LIVE;
    case SCHEDULED_CANCELED;
}
