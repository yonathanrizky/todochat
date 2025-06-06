<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Services\ChatGptService;
use App\DataTables\NewsDataTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NewsPostRequest;

class NewsController extends Controller
{
    protected $chat;

    public function __construct(ChatGptService $chat)
    {
        $this->chat = $chat;
    }

    public function index(NewsDataTable $dataTable)
    {
        return $dataTable->render('pages.news.index', ['type_menu' => 'news']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.news.create', ['type_menu' => 'news']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsPostRequest $request)
    {
        $validated = $request->validated();

        $news = new News();
        $news->title = $request->title;
        $news->content = $request->content;

        $news->save();
        $notification = [
            'message' => 'Data Berhasil Dibuat',
            'alert-type' => 'success'
        ];

        return redirect()->route('news.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        return view('pages.news.show', ['type_menu' => 'news', 'news' => $news]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(NewsPostRequest $request, News $news)
    {
        $validated = $request->validated();

        $news->title = $request->title;
        $news->content = $request->content;

        $news->save();
        $notification = [
            'message' => 'Data Berhasil Diubah',
            'alert-type' => 'success'
        ];

        return redirect()->route('news.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $news->delete();
        $notification = [
            'message' => 'Data Berhasil Dihapus',
            'alert-type' => 'success'
        ];

        return redirect()->route('news.index')->with($notification);
    }

    public function search(Request $request)
    {
        $query = $request->description;
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
                return response()->json(['error' => 'Query gagal: ' . $e->getMessage()], 400);
            }
        }

        if (!count($data))
        {
            $data = $this->chat->chat($query);
        }

        $chat_id = DB::table('chats')->insertGetId([
            'customer_id' => $customer_id
        ]);

        $chats[] = [
            'chat_id' => $chat_id,
            'message' => $input,
            'bot' => 0
        ];

        foreach ($data as $item)
        {
            $chats[] = [
                'chat_id' => $chat_id,
                'message' => $item->content,
                'bot' => 1
            ];
        }

        $conversation = DB::table('conversations')->insert($chats);

        return response()->json($data);
    }
}
