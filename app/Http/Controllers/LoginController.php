<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('layouts.formLogin');
    }

    public function login(Request $request)
    {
        $loginType = filter_var($request->input('nama'), FILTER_VALIDATE_EMAIL) ? 'email' : 'nama';

        $credentials = [
            $loginType => $request->input('nama'),
            'password' => $request->input('password')
        ];

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/adminprov');
        }

        return redirect()->back()->withErrors(['login' => 'Gagal login, silakan coba lagi.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
