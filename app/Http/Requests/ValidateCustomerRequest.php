<?php

namespace App\Http\Requests;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ValidateCustomerRequest extends FormRequest
{
    use  FailedValidation;

    public function rules(): array
    {
        return [
            'customer' => ['required', 'string', 'exists:customers,device_id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
