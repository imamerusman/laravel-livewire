<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;

class OpenAI {
    public static function getAiResponse(string $prompt): array
    {   $key = config('app.openai_key');
        $response = Http::withHeaders([

            'Content-Type'  => 'application/json',
            'Authorization' => "Bearer $key",

        ])->post('https://api.openai.com/v1/chat/completions', [
            "model" => "gpt-3.5-turbo",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "only response in json format I will have to parse it directly."
                ],
                [
                    "role" => "user",
                    "content" => "$prompt let\'s Generate a title within 8 words and small description maximum 30 words
                     for notification that will be sent to user."
                ],
            ]
        ]);
        $decodeResponse = json_decode($response->body());
        $choices = collect($decodeResponse->choices);
        if ($choices->isEmpty()) {
            return [
                'title' => 'The title did not generate due to some error.',
                'description' => 'The description did not generate due to some error.',
            ];
        }
        $choice = $choices->first();
        $content = $choice->message->content;
        $decodeContent = json_decode($content);
        return [
            'title' => $decodeContent->title,
            'description' => $decodeContent->description,
        ];
    }

}
