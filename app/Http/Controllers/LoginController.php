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
            // Redirect berdasarkan role
            $user = Auth::user();

            if ($user->id_role == 1) {
                // Admin Provinsi
                return redirect()->intended('/AdminProv');
            } elseif ($user->id_role == 2) {
                // Admin Kabkot
                return redirect()->intended('/Kabkot');
            } else {
                // Role tidak dikenal
                Auth::logout();
                return redirect()->back()->withErrors(['login' => 'Role tidak valid!']);
            }
        }

        return redirect()->back()->withErrors(['login' => 'Gagal login, periksa kembali username/email dan password Anda!']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
