<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Conversation\NewMessageRequest;
use App\Http\Requests\ValidateCustomerRequest;
use App\Http\Resources\Api\Message\ConversationDetailsResource;
use App\Http\Resources\Api\Message\MessageListResource;
use App\Models\Customer;
use App\Traits\CanResponseTrait;
use Exception;

class ConversationController extends Controller
{
    use CanResponseTrait;

    public function send(NewMessageRequest $request)
    {
        $user = $request->user();
        $customer = Customer::whereDeviceId($request->input('sender_device_id'))->first();
        $response = $customer->sendMessage(
            to: $user,
            content: $request->hasFile('file')
                ? $request->file('file')
                : $request->input('message'),
        );
        return filled($response['message'])
            ? $this->success(data: new MessageListResource($response['message']), message: 'Message sent successfully')
            : $this->error(data: $response, message: 'Message not sent', code: 500);
    }

    /**
     * @throws Exception
     */
    public function conversation(ValidateCustomerRequest $request)
    {
        $authUser = request()->user();
        $customer = Customer::whereDeviceId($request->get('customer'))->first();
        $userConversation = $customer->findOrCreateConversation($authUser);
        $userConversation->markAsRead();
        $conversation = new ConversationDetailsResource($userConversation);
        return $this->success(data: $conversation);
    }
}
