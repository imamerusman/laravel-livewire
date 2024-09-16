<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateCustomerRequest;
use App\Models\Customer;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function create(CreateCustomerRequest $request)
    {
        $customer = Customer::whereEmail($request->get('email'))->first();
        $updatableData = [
            'name' => $request->get('name', Str::before($request->get('email'), '@')),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'shopify_id' => $request->get('shopify_id'),
            'device_token' => $request->get('device_token'),
            'timezone' => $request->get('timezone'),
        ];
        if(filled($customer)){
            $customer->update([
                'device_id' => $request->get('device_id'),
                ...$updatableData
            ]);
        }else{
            $customer = $request->user()->customers()
                ->updateOrCreate([
                    'device_id' => $request->get('device_id'),
                ], $updatableData);
        }
        return isset($customer)
            ? $this->success($customer, 'Customer created successfully')
            : $this->error('Customer not created');
    }

    private function extractID(string $shopifyCustomerId): int
    {
        $customerString = $shopifyCustomerId;

        $parts = explode("/", $customerString);

        return (int)end($parts);
    }
}
