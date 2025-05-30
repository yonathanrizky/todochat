<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ChatGptService;

class NewsController extends Controller
{
    protected $chat;

    public function __construct(ChatGptService $chat)
    {
        $this->chat = $chat;
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $prompt = "Buatkan query SQL SELECT sederhana dari tabel news untuk mencari berita berdasarkan: \"$query\". Gunakan WHERE yang sesuai, dan hanya SELECT dari tabel news.";
        $sql = $this->chat->ask($prompt);

        try
        {
            $results = DB::select(DB::raw($sql));
            return response()->json($results);
        }
        catch (\Exception $e)
        {
            return response()->json(['error' => 'Query gagal: ' . $e->getMessage()], 400);
        }
    }
}
