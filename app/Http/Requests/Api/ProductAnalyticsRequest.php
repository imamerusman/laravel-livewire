<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ValidateCustomerRequest;
use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ProductAnalyticsRequest extends ValidateCustomerRequest
{
    use FailedValidation;
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'shopify_product_id' => ['required'],
            'name' => ['required'],
            'views' => ['nullable', 'integer'],
            'sales' => ['nullable', 'integer'],
            'searches' => ['nullable', 'integer'],
        ];
    }

}
