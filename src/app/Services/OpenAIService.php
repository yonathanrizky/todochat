<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    public function chat($prompt)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return $response->json()['choices'][0]['message']['content'] ?? 'Tidak ada respon.';
    }
}
