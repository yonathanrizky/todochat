<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Http\Requests\UserPostRequest;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersDataTable $usersDataTable)
    {
        return $usersDataTable->render('pages.user.index', ['type_menu' => 'pengguna']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.user.create', ['type_menu' => 'pengguna']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserPostRequest $request)
    {
        $validated = $request->validated();

        $user = new User();
        $user->nik = $request->nik;
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->admin = $request->admin == 'on' ? true : false;
        $user->save();
        $notification = [
            'message' => 'Data Berhasil Dibuat',
            'alert-type' => 'success'
        ];

        return redirect()->route('user.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('pages.user.show', ['type_menu' => 'user', 'user' => $user]);
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
    public function update(Request $request, User $user)
    {
        $user->admin = $request->admin ? true : false;
        $user->nik = $request->nik;
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if ($request->password)
        {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $notification = [
            'message' => 'Data Berhasil Diubah',
            'alert-type' => 'success'
        ];

        return redirect()->route('user.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        $notification = [
            'message' => 'Data Berhasil Dihapus',
            'alert-type' => 'success'
        ];

        return redirect()->route('user.index')->with($notification);
    }
}
