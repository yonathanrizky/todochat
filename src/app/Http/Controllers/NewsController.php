<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Services\ChatGptService;
use App\DataTables\NewsDataTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NewsPostRequest;
use Illuminate\Support\Facades\Session;

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
}
