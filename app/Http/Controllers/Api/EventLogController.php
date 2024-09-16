<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateCustomerRequest;
use App\Models\Customer;
use Symfony\Component\HttpFoundation\Response;

class EventLogController extends Controller
{
    public function logCartAbandoned(ValidateCustomerRequest $request)
    {
        $customer = Customer::whereDeviceId($request->string('customer'))->first();
        $customer->cartAbandonmentEvents()->create([
            'cart_id' => $request->string('cart_id'),
        ]);
        return $this->success(message: 'Cart abandonment event logged successfully.', code: Response::HTTP_CREATED);
    }

    public function logCartResolved(ValidateCustomerRequest $request)
    {
        $customer = Customer::whereDeviceId($request->string('customer'))->first();
        $cart = $customer->cartAbandonmentEvents()->where('cart_id', $request->string('cart_id'))->first();
        if ($cart === null) {
            return $this->error(message: 'Cart not found.', code: Response::HTTP_NOT_FOUND);
        }
        $cart->markAsResolved();
        return $this->success(message: 'Cart Resolved successfully.');
    }

    public function logAppTerminated(ValidateCustomerRequest $request)
    {
        $customer = Customer::whereDeviceId($request->string('customer'))->first();
        $customer->appTerminationEvents()->create();
        return $this->success(message: 'App termination event logged successfully.', code: Response::HTTP_CREATED);
    }

    public function readNotification(ValidateCustomerRequest $request)
    {
        $customer = Customer::whereDeviceId($request->string('customer'))->first();

        if ($customer === null) {
            return $this->error(message: 'Customer not found.', code: Response::HTTP_NOT_FOUND);
        }

        $notificationId = $request->input('notification_id');
        $notificationType = $request->input('notification_type');
        $notifications = $notificationType::find($notificationId);

        $notifications?->increment('open_count');

        return $this->success(
            data: [
                'notification_modal' => $notificationType,
                'notification_type' => $notifications->type ?? null,
            ],
            message: 'Notification read successfully.',
            code: Response::HTTP_CREATED
        );

    }

    public function saleNotification(ValidateCustomerRequest $request)
    {
        $customer = Customer::whereDeviceId($request->string('customer'))->first();

        if ($customer === null) {
            return $this->error(message: 'Customer not found.', code: Response::HTTP_NOT_FOUND);
        }

        $notificationId = $request->input('notification_id');
        $notificationType = $request->input('notification_type');
        $notifications = $notificationType::find($notificationId);
        $notifications?->increment('sale_count');

        return $this->success(
            data: [
                'notification_modal' => $notificationType,
                'notification_type' => $notifications->type ?? "schedule_notification",
            ],
            message: 'Notification read successfully.',
            code: Response::HTTP_CREATED
        );
    }
}
