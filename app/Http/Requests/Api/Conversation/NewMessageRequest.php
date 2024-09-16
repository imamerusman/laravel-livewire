<?php

namespace App\Http\Requests\Api\Conversation;

use App\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class NewMessageRequest extends FormRequest
{
    use FailedValidation;

    public function rules(): array
    {
        return [
            'sender_device_id' => ['required', 'string', 'exists:customers,device_id'],
            'file' => ['nullable', 'file', 'max:60000', 'mimes:jpg,jpeg,png,doc,docx,pdf,heif,heic,hevc,mp4,mp3,webm,ogg,txt,zip'],
            'message' => ['required_without:file', 'string'],
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
