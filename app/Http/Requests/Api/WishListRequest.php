<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ValidateCustomerRequest;
use App\Traits\FailedValidation;

class WishListRequest extends ValidateCustomerRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'item' => ['string', 'required', 'max:255'],
            ...parent::rules()
        ];
    }
}
