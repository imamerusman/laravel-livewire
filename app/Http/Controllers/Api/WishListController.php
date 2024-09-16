<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\WishListRequest;
use App\Http\Requests\ValidateCustomerRequest;
use App\Http\Resources\Api\WishListResource;
use App\Models\Customer;

class WishListController extends Controller
{
    public function list(ValidateCustomerRequest $request)
    {
        $customer = Customer::whereDeviceId($request->string('customer'))->first();

        return $this->success(data: new WishListResource($customer->wish_list_model));
    }

    public function store(WishListRequest $request)
    {
        $customer = Customer::whereDeviceId($request->string('customer'))->first();
        $customer->syncWishList($request->string('item'));

        return $this->success(data: new WishListResource($customer->wish_list_model), message: 'Item added to wish list');
    }
}
