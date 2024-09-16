<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenAiController extends Controller
{
    public function testing()
    {
        return ' {
                "id": "chatcmpl-7qd1n6FZTpsWl3s5mIAgqi0mdTDa3",
                "object": "chat.completion",
                "created": 1692777435,
                "model": "gpt-3.5-turbo-0613",
                "choices": [
                    {
                        "index": 0,
                        "message": {
                            "role": "assistant",
                            "content": "{\n  \"title\": \"Welcome Back!\",\n  \"description\": \"We missed you! Explore new features and enjoy the app.\"\n}"
                        },
                        "finish_reason": "stop"
                    }
                ],
                "usage": {
                    "prompt_tokens": 69,
                    "completion_tokens": 27,
                    "total_tokens": 96
                }
            }';


    }



}
