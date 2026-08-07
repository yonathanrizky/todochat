<?php

namespace App\Http\Controllers;

use App\Models\Complain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DataTables\ComplainCustomersDataTable;

class ComplainCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ComplainCustomersDataTable $dataTable)
    {
        return $dataTable->render('pages.complain_customer.index', ['type_menu' => 'complain']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $complain = Complain::find($id);
        $chats = DB::select("
        select c.message, c.bot
        from conversations c
        join chats ch on ch.id = c.chat_id
        where ch.code = '{$complain->ticket_num}'
        order by c.created_at
        ");
        
        return view('pages.complain_customer.show', [
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
    
    public function updateStatus(Complain $complain)
    {
        $complain->update(['status' => true]);
        
        $notification = [
            'message' => 'Keluhan berhasil ditandai selesai.',
            'alert-type' => 'success'
        ];
    
        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complain $complain)
    {
        $complain->delete();
        $notification = [
            'message' => 'Data Berhasil Dihapus',
            'alert-type' => 'success'
        ];

        return redirect()->route('complain-customer.index')->with($notification);
    }
}
