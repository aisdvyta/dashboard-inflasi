<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TambahAkunController extends Controller
{
    public function index()
    {
        // Mengambil semua data pengguna dengan relasi satker dan role
        $users = User::with(['satker', 'role'])->get();
        return view('prov.manajemen-akun.index', compact('users'));
    }

    public function create()
    {
        // Menampilkan form tambah akun
        return view('prov.manajemen-akun.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|max:255|unique:penggunas,username',
            'kode_satker' => 'required|numeric|digits:4',
            'nama_satker' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
        ]);

        // Membuat akun baru
        User::create([
            'id' => Str::uuid(), // Menggunakan UUID sebagai ID
            'username' => $request->username,
            'kode_satker' => $request->kode_satker,
            'nama_satker' => $request->nama_satker,
            'role' => $request->role,
            'password' => bcrypt('password123'), // Default password
        ]);

        return redirect()->route('manajemen-akun.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Mengambil data pengguna berdasarkan ID
        $user = User::with(['satker', 'role'])->findOrFail($id);
        return view('prov.manajemen-akun.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Mengambil data pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Validasi input
        $request->validate([
            'username' => 'required|string|max:255|unique:penggunas,username,' . $user->id,
            'kode_satker' => 'required|numeric|digits:4',
            'nama_satker' => 'required|string|max:255',
            'role' => 'required|in:admin,user',
        ]);

        // Memperbarui data pengguna
        $user->update($request->all());

        return redirect()->route('manajemen-akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Menghapus data pengguna berdasarkan ID
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('manajemen-akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}
