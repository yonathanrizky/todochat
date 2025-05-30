<?php

namespace App\Http\Controllers;

use File;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pegawai_id = Auth::user()->pegawai_id;
        $pegawai = DB::select("
        select e.id, e.nik, e.fullname, e.phone, e.email, e.avatar, ps.description as position,
        dp.description as department, dv.description as divisi, e.signature
        from employees e
        join departments dp on dp.id = e.department_id
        join divisions dv on dv.id = e.division_id
        join positions ps on ps.id = e.position_id
        where e.id = {$pegawai_id}
        ")[0];


        return view('pages.profile.index', ['type_menu' => 'profile', 'pegawai' => $pegawai]);
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
        $pegawai = Pegawai::find($id);
        if ($request->file)
        {
            if ($pegawai->avatar != 'avatar-1.png')
                File::delete('/public/img/avatar/' . $pegawai->avatar);

            $file = $request->file;
            $file_extension = $file->getClientOriginalExtension();

            $filename = uniqid() . '.' . $file_extension;
            $file->move('img/avatar', $filename);
            $pegawai->avatar = $filename;
        }

        if ($request->signature)
        {
            $folderPath = 'img/signature/';
            File::delete('/public/img/signature/' . $pegawai->signature);

            $image = $request->signature;
            $imageInfo = explode(";base64,", $image);
            $imgExt = str_replace('data:image/', '', $imageInfo[0]);
            $image = str_replace(' ', '+', $imageInfo[1]);
            // $imageName = "post-" . time() . "." . $imgExt;
            $imageName = uniqid() . '.' . $imgExt;
            file_put_contents($folderPath . $imageName, base64_decode($image));
            $pegawai->signature = $imageName;
        }

        $pegawai->email = $request->email;
        $pegawai->phone = $request->phone;

        $pegawai->save();

        if ($request->password)
        {
            DB::table('users')->where('nik', $pegawai->nik)->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $notification = [
            'message' => 'Data Berhasil Diubah',
            'alert-type' => 'success'
        ];

        return redirect()->route('profile')->with($notification);
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
