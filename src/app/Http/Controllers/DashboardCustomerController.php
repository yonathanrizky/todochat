<?php

namespace App\Http\Controllers;

use App\Models\Complain;
use Illuminate\Http\Request;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Auth;

class DashboardCustomerController extends Controller
{

    protected $openai;

    public function __construct(OpenAIService $openai)
    {
        $this->openai = $openai;
    }

    public function index()
    {
        $customer_id = Auth::guard('web')->user()->id;
        $count = Complain::where('customer_id', $customer_id)->get()->count();
        return view('pages.dashboard_customer.index', [
            'type_menu' => 'dashboard',
            'count' => $count
        ]);
    }
}
