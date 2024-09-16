<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductAnalyticsRequest;
use App\Models\Events\CartAbandonmentEvent;
use App\Models\ProductAnalytics;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationAnalyticsController extends Controller
{
    public function getNotificationAnalytics()
    {
        $statuses = ['send', 'pending','failed'];

        $monthsData = \App\Models\NotificationAnalytics::whereIn('status', $statuses)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('M');
            })
            ->map(function ($item) {
                return [
                    'send' => $item->where('status', 'send')->count(),
                    'pending' => $item->where('status', 'pending')->count(),
                    'failed' => $item->where('status', 'failed')->count(),
                ];
            });

        return [
             'labels' => $monthsData->keys(),
            'pending'=> [
                'label' => 'Pending Notification',
                'data' => $monthsData->values()->pluck('pending')->toArray()
            ],
            'send'=> [
                'label' => 'Send Notification',
                'data' => $monthsData->values()->pluck('send')->toArray()
            ],

            'failed'=> [
                'label' => 'Failed Notification',
                'data' => $monthsData->values()->pluck('failed')->toArray()
            ]

        ];
    }
    public function getCartAbandonmentNotificationAnalytics()
    {

        $cartAbandonmentCount = CartAbandonmentEvent::count('status');
        $resolvedStatus = CartAbandonmentEvent::whereStatus('resolved')->count('status');
        $unresolvedStatus = CartAbandonmentEvent::whereStatus('unresolved')->count('status');

        return [
             'label' => 'User Cart Abandonment Notification Analytics',
            'total_cart_abandonment' => [
                'label' => 'Total Abandonment Notification',
                'data' => $cartAbandonmentCount
            ],
            'resolved'=> [
                'label' => 'Total Resolved',
                'data' => $resolvedStatus
            ],
            'unresolved'=> [
                'label' => 'Total Unresolved',
                'data' => $unresolvedStatus
            ]

        ];
    }
}
