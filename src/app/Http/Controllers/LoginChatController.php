<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginChatController extends Controller
{
    public function index()
    {
        return view('pages.auth_customer.login');
    }

    public function login(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (Auth::guard('web')->attempt($data))
        {
            return redirect('/');
        }
        else
        {
            $notification = [
                'message' => 'Email / Password Salah',
                'alert-type' => 'error'
            ];

            return redirect()->route('login')->with($notification);
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect('/login');
    }
}
