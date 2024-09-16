<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YouTubeCallbackRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'scope' => 'required|string',
        ];
    }
}
