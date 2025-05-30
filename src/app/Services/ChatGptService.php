<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatGptService
{
    protected $apiKey;
    protected $endpoint;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        $this->endpoint = 'https://api.openai.com/v1/chat/completions';
    }

    public function ask(string $prompt): string
    {
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'Kamu adalah asisten untuk membantu membuat query SQL untuk MySQL.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                // 'temperature' => 0.2,
            ]);

        if ($response->successful())
        {
            return $response->json('choices.0.message.content') ?? '';
        }
        return '';
    }

    public function extractKeywords(string $input): array
    {
        $prompt = <<<PROMPT
        Ambil kata kunci penting dari kalimat ini untuk pencarian: "$input". Kembalikan sebagai array JSON. Contoh: ["keyword1", "keyword2"]
        PROMPT;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->endpoint, [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.2,
        ]);

        $result = $response->json();
        $content = $result['choices'][0]['message']['content'] ?? '[]';

        return json_decode($content, true);
    }

    public function generateProductQuery(string $input): string
    {
        // Buat query SQL sederhana dari tabel `products` untuk mencari data berdasarkan kalimat: "$input".
        $prompt = <<<PROMPT
        Buat query SQL sederhana dari tabel `products` untuk mencari data berdasarkan kalimat: "$input".
        Gunakan SELECT * FROM products WHERE ...
        Jangan gunakan MATCH, full-text, atau JOIN. Jangan pakai titik koma. Berikan hasil hanya syntax SQL.
        Pastikan valid untuk MariaDB.
        PROMPT;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->endpoint, [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.2,
        ]);

        return trim($response->json()['choices'][0]['message']['content'] ?? '');
    }

    public function chat($prompt)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        // return $response->json()['choices'][0]['message']['content'] ?? 'Tidak ada respon.';
        return trim($response->json()['choices'][0]['message']['content'] ?? '');
    }
}
