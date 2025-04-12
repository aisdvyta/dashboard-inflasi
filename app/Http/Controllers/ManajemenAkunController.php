<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManajemenAkunController extends Controller
{
    // public function index()
    // {
    //     // Mengambil semua data pengguna dengan relasi satker dan role
    //     $users = User::orderBy('id_satker', 'asc')->get();
    //     return view('prov.manajemen-akun.index', compact('users'));
    // }

    public function index(Request $request)
    {
        // Ambil input pencarian
        $search = $request->input('search');

        // Query pengguna dengan relasi ke role dan satker
        $users = User::query()
            ->with(['role', 'satker']) // Pastikan relasi role dan satker sudah didefinisikan di model User
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('role', function ($query) use ($search) {
                        $query->where('nama_role', 'like', "%{$search}%"); // Cari berdasarkan nama role
                    })
                    ->orWhereHas('satker', function ($query) use ($search) {
                        $query->where('kode_satker', 'like', "%{$search}%") // Cari berdasarkan kode satker
                            ->orWhere('nama_satker', 'like', "%{$search}%"); // Cari berdasarkan nama satker
                    });
            })
            ->orderBy('id_satker', 'asc')
            ->paginate(10);

        return view('prov.manajemen-akun.index', compact('users'));
    }

    public function create()
    {
        $satkers = \App\Models\master_satker::all(); // Ambil semua data satker
        return view('prov.manajemen-akun.create', compact('satkers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:penggunas,nama',
            'email' => 'required|email|regex:/@bps\.go\.id$/|unique:penggunas,email',
            'id_satker' => 'required|exists:master_satkers,kode_satker',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'id' => Str::uuid(),
            'nama' => $request->nama,
            'email' => $request->email,
            'id_satker' => $request->id_satker,
            'id_role' => 2,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('manajemen-akun.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit($id)
    {
        dd:
        ($id); // Debugging: Tampilkan nilai $id
        $user = User::with(['satker', 'role'])->findOrFail($id);

        // Ambil data satker untuk dropdown
        $satkers = \App\Models\master_satker::all();

        // Kirim data user dan satker ke view edit
        return view('prov.manajemen-akun.edit', compact('user', 'satkers'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255|unique:penggunas,nama,' . $id,
            'email' => 'required|email|regex:/@bps\.go\.id$/|unique:penggunas,email,' . $id,
            'id_satker' => 'required|exists:master_satkers,kode_satker',
            'password' => 'nullable|string|min:6', // Password opsional saat edit
        ]);

        // Ambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // Update data user
        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'id_satker' => $request->id_satker,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('manajemen-akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            // Menghapus data pengguna berdasarkan ID
            $user = User::findOrFail($id);
            $user->delete();

            // Set flash message untuk berhasil
            return redirect()->route('manajemen-akun.index')->with('status', 'success');
        } catch (\Exception $e) {
            // Set flash message untuk gagal
            return redirect()->route('manajemen-akun.index')->with('status', 'error');
        }
    }
}
