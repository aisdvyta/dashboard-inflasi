<?php

namespace App\Http\Controllers;

use App\Models\master_satker;
use App\Http\Requests\StoreMaster_satkerRequest;
use App\Http\Requests\UpdateMaster_satkerRequest;
use Illuminate\Http\Request;

class MasterSatkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil semua data master satker dari database
        $search = $request->input('search'); // Ambil input pencarian

        $satkers = master_satker::query()
            ->when($search, function ($query, $search) {
                return $query->where('kode_satker', 'like', "%{$search}%")
                    ->orWhere('nama_satker', 'like', "%{$search}%");
            })
            ->orderBy('kode_satker', 'asc')
            ->paginate(10);
        return view('prov.master-satker.index', compact('satkers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_satker' => 'required|numeric|digits:4|unique:master_satkers,kode_satker',
            'nama_satker' => 'required|string|max:255',
        ]);

        master_satker::create([
            'kode_satker' => $request->kode_satker,
            'nama_satker' => $request->nama_satker,
        ]);

        return redirect()->route('master-satker.index')->with('success', 'Satker berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kode_satker)
    {
        // Ambil data satker berdasarkan kode_satker
        $satker = master_satker::where('kode_satker', $kode_satker)->firstOrFail();
        return response()->json($satker);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_satker)
    {
        // Validasi input
        $request->validate([
            'kode_satker' => 'required|numeric|digits:4|unique:master_satkers,kode_satker,' . $kode_satker . ',kode_satker',
            'nama_satker' => 'required|string|max:255',
        ]);

        // Update data satker
        $satker = master_satker::where('kode_satker', $kode_satker)->firstOrFail();
        $satker->update([
            'nama_satker' => strtoupper($request->nama_satker), // Pastikan nama satker disimpan dalam huruf kapital
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('master-satker.index')->with('success', 'Satker berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(master_satker $kode_satker)
    {
        try {
            // Menghapus data satker berdasarkan kode_satker
            $kode_satker->delete();

            // Set flash message untuk berhasil
            return redirect()->route('master-satker.index')->with('status', 'success');
        } catch (\Exception $e) {
            // Set flash message untuk gagal
            return redirect()->route('master-satker.index')->with('status', 'error');
        }
    }
}
