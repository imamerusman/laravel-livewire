<?php

namespace App\Http\Requests\Api;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'shopify_id' => ['nullable', 'string', 'max:254'],
            'name' => ['nullable', 'string', 'max:254'],
            'email' => ['nullable', 'email', 'max:254'],
            'phone' => ['nullable', 'max:255'],
            'device_id' => ['nullable', 'max:255'],
            'device_token' => ['required', 'max:255'],
            'timezone' => ['required', 'timezone', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'timezone.timezone' => 'Timezone must be a valid timezone string, e.g. America/New_York.',
        ];
    }
}
