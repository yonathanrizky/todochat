<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatGptService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    protected $chat;

    public function __construct(ChatGptService $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Session::forget('chat_id');
        return view('pages.chat.index', [
            'type_menu' => 'chat'
        ]);
    }

    public function handle(Request $request)
    {
        $input = $request->message;

        // Klasifikasi input: products atau news
        $classificationPrompt = "Klasifikasikan kalimat ini sebagai 'products' atau 'informations' saja: \"$input\". Jawab hanya dengan satu kata: products atau informations.";
        $category = trim(strtolower($this->chat->ask($classificationPrompt)));

        if ($category === 'products')
        {
            return $this->product($request);
        }
        elseif ($category === 'informations')
        {
            return $this->news($request);
        }
        else
        {
            return response()->json([
                'bot' => 'Terjadi Kegagalan,',
            ]);
            return response()->json(['error' => 'Kategori pencarian tidak dikenali, hasil: ' . $category], 400);
        }
    }

    public function product(Request $request)
    {
        $customer_id = Auth::guard('web')->user()->id;
        $query = strip_tags($request->message);

        $input = $query;

        $keywords = $this->chat->extractKeywords($input);

        $queryData = DB::table('products');

        foreach ($keywords as $keyword)
        {
            $queryData->orWhere('product_name', 'like', "%$keyword%")
                ->orWhere('description', 'like', "%$keyword%")
                ->orWhere('price', 'like', "%$keyword%");
        }
        $data = $queryData->get();

        if (!count($data))
        {
            try
            {
                $sql = $this->chat->generateProductQuery($query);
                $data = DB::select(DB::raw($sql));
            }
            catch (\Exception $e)
            {
                // return $this->handle($request);
                return response()->json(['error' => 'Query gagal: ' . $e->getMessage()], 400);
            }
        }

        if (count(($data)))
        {
            $botReply = "";
            foreach ($data as $value)
            {
                $botReply .=  "Paket " . $value->product_name . "\n";
                $botReply .=  "Dengan Harga " . number_format($value->price, 2) . "\n";
                $botReply .=  $value->description . "\n\n";
            }
        }

        if (!count($data))
        {
            $botReply = $this->chat->chat($query);
        }

        $chatId = Session::get('chat_id');
        if (!$chatId)
        {
            $chatId = DB::table('chats')->insertGetId([
                'customer_id' => $customer_id,
                'code' => time()
            ]);
            session(['chat_id' => $chatId]);
        }

        $chats = [
            [
                'chat_id' => $chatId,
                'message' => $input,
                'bot' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'chat_id' => $chatId,
                'message' => $botReply,
                'bot' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        $conversation = DB::table('conversations')->insert($chats);

        return response()->json([
            'user' => $query,
            'bot' => $botReply,
        ]);
    }

    public function news(Request $request)
    {
        $query = strip_tags($request->message);
        $customer_id = Auth::guard('web')->user()->id;
        $input = $query;

        $keywords = $this->chat->extractKeywords($input);

        $queryData = DB::table('news');

        foreach ($keywords as $keyword)
        {
            $queryData->Where('content', 'like', "%$keyword%");
        }
        $data = $queryData->get();

        if (!count($data))
        {
            try
            {
                $sql = $this->chat->generateNewsQuery($query);
                $data = DB::select(DB::raw($sql));
            }
            catch (\Exception $e)
            {
                // return $this->handle($request);
                return response()->json(['error' => 'Query gagal: ' . $e->getMessage()], 400);
            }
        }

        if (count(($data)))
        {
            $botReply = "";
            foreach ($data as $value)
            {
                $botReply .= $value->content . "\n";
            }
        }

        if (!count($data))
        {
            $botReply = $this->chat->chat($query);
        }

        $chatId = Session::get('chat_id');
        if (!$chatId)
        {
            $chatId = DB::table('chats')->insertGetId([
                'customer_id' => $customer_id,
                'code' => time()
            ]);
            session(['chat_id' => $chatId]);
        }

        $chats = [
            [
                'chat_id' => $chatId,
                'message' => $input,
                'bot' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'chat_id' => $chatId,
                'message' => $botReply,
                'bot' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        DB::table('conversations')->insert($chats);

        return response()->json([
            'user' => $query,
            'bot' => $botReply,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
