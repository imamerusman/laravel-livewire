<?php

namespace App\Notifications;

use App\Models\Customer;
use App\Models\FirebaseCredential;
use App\Models\NotificationAnalytics;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Exception\RuntimeException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseChannel
{
    /**
     * Send the given notification.
     * @throws MessagingException
     * @throws FirebaseException
     */
    public function send(Model $notifiable, Notification $notification): void
    {
        /** @var User|Customer $notifiable */
        $messaging = $this->connect($notifiable->user->firebaseCredentials);

        if (!method_exists($notification, 'toCloudMessage')) {
            throw new RuntimeException(
                '`toCloudMessage` method not implemented for this notification: ' . get_class($notification)
            );
        }
        $payload = [];
        if (method_exists($notification, 'getPayloadData')) {
            $payload = $notification->getPayloadData();
        }
        $customer = [];
        if (method_exists($notification, 'getCustomer'))
        {
            $customer = $notification->getCustomer();
        }
        $notification = $notification->toCloudMessage($notifiable);
        $deviceToken = $notifiable->device_token;

        if (!isset($deviceToken)) {
            throw new RuntimeException('device_token not set for this user: ' . $notifiable->email);
        }
        try {
            $messaging->send(message: CloudMessage::new()
                ->withTarget('token', $deviceToken)
                ->withData($payload)
                ->withNotification($notification));
            NotificationAnalytics::create([
                'user_id' => $customer['user_id'],
                'notification_id' => $payload['id'],
                'customer_id' => $customer['id'],
                'notification_type' => $payload['type'],
                'status' => 'pending'
            ]);
        }catch (MessagingException $e) {
            throw $e;
        }
    }
    public function connect(FirebaseCredential $firebaseCredential): Messaging
    {
        $firebase = (new Factory)
            ->withServiceAccount($firebaseCredential->toArray());
        return $firebase->createMessaging();
    }
}
