<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;

class DashboardCustomerController extends Controller
{

    protected $openai;

    public function __construct(OpenAIService $openai)
    {
        $this->openai = $openai;
    }

    public function index()
    {
        // $query = "berikan informasi terbaru";
        // $prompt = "apakah kalimat $query ini mengandung huruf info, berikan nilai 1 jika ya, 2 jika tidak";
        // $response = $this->openai->chat($prompt);
        // dd($response);
        return view('pages.dashboard_customer.index', [
            'type_menu' => 'dashboard',
        ]);
    }
}
