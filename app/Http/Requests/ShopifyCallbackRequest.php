<?php

namespace App\Http\Requests;

use App\Rules\Base64ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ShopifyCallbackRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'size:32'],
            'hmac' => ['required', 'string', 'size:64'], // assuming the HMAC is an SHA-256 hash in hex format
            'host' => ['required', 'string', new Base64ValidationRule], // validate as base64 encoded string
            'shop' => ['required', 'string', 'regex:/^[a-z0-9-]+\.myshopify\.com$/'],
            'timestamp' => ['required', 'string', 'size:10'] // assuming the timestamp is a UNIX timestamp
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
