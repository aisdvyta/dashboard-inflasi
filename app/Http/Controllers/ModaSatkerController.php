<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModaSatkerController extends Controller
{
    public function index()
    {
        return view('components.modaTambahSatker');
    }

    public function modasatker(Request $request)
    {
        $request->validate([
            'kodeSatker' => 'required',
            'namaSatker' => 'required|string',
        ]);

        if (Auth::attempt([
            'kodeSatker' => $request->input('kodeSatker'),
            'namaSatker' => $request->input('namaSatker'),
        ])) {
            return redirect()->intended('/#');
        }

        return redirect()->back()->withErrors(['login' => 'Satker telah tersedia, silahkan coba lagi.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
