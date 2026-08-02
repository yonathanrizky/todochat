<?php

namespace App\Http\Controllers;

use App\Models\Complain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\DataTables\ComplainsDataTable;
use Illuminate\Support\Facades\Session;

class ComplainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ComplainsDataTable $dataTable)
    {
        return $dataTable->render('pages.complain.index', ['type_menu' => 'complain']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.complain.create', [
            'type_menu' => 'complain'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $complain = new Complain();
        $description = $request->description;
        $fullname = $request->fullname;
        $kelurahan = $request->kelurahan;
        $address = $request->address;
        $customer_id = Auth::guard('web')->user()->id;
        $ticketNum = Session::get('ticket_num');
        if (!$ticketNum)
        {
            $ticketNum = time();
        }

        $complain->ticket_num = $ticketNum;
        $complain->description = $description;
        $complain->customer_id = $customer_id;
        $complain->fullname = $fullname;
        $complain->kelurahan = $kelurahan;
        $complain->address = $address;
        $complain->save();

        $notification = [
            'message' => 'Data Berhasil Dibuat',
            'alert-type' => 'success'
        ];

        return redirect()->route('dashboard')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Complain $complain)
    {
        $chats = DB::select("
        select c.message, c.bot
        from conversations c
        join chats ch on ch.id = c.chat_id
        where ch.code = '{$complain->ticket_num}'
        order by c.created_at
        ");
        return view('pages.complain.show', [
            'type_menu' => 'complain',
            'complain' => $complain,
            'chats' => $chats
        ]);
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
