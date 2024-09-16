<?php

namespace App\Models\Events;

enum EventStatusTypes: string
{
    case RESOLVED = 'resolved';
    case UNRESOLVED = 'unresolved';
    case PENDING = 'pending';
    case FAILED = 'failed';
}
