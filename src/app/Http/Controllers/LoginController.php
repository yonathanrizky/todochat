<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (Auth::guard('admin')->attempt($data))
        {
            return redirect('/admin/');
        }
        else
        {
            $notification = [
                'message' => 'Email / Password Salah',
                'alert-type' => 'error'
            ];

            return redirect()->route('admin.login')->with($notification);
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
