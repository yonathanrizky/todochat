<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatGptService;

class SearchController extends Controller
{
    protected $chat;

    public function __construct(ChatGptService $chat)
    {
        $this->chat = $chat;
    }

    public function handle(Request $request)
    {
        // $input = $request->input('query');
        $input = $request->description;

        // Klasifikasi input: products atau news
        $classificationPrompt = "Klasifikasikan kalimat ini sebagai 'products' atau 'news' saja: \"$input\". Jawab hanya dengan satu kata: products atau news.";
        $category = trim(strtolower($this->chat->ask($classificationPrompt)));

        if ($category === 'products')
        {
            return app(ProductController::class)->search($request);
        }
        elseif ($category === 'news')
        {
            return app(NewsController::class)->search($request);
        }
        else
        {
            return response()->json(['error' => 'Kategori pencarian tidak dikenali, hasil: ' . $category], 400);
        }
    }
}
