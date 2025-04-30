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
        $request->validate([
            'nama' => 'required|string',
            'password' => 'required',
        ]);

        // Pastikan kolom login sesuai dengan database
        $loginType = filter_var($request->input('nama'), FILTER_VALIDATE_EMAIL) ? 'email' : 'nama';

        if (Auth::attempt([
            $loginType => $request->input('nama'),
            'password' => $request->input('password')
        ])) {
            return redirect()->intended('/AdminProv');
        }

        return redirect()->back()->withErrors(['login' => 'Gagal login, periksa kembali username/email dan password Anda!']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
