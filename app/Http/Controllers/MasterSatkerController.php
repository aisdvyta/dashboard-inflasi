<?php

namespace App\Http\Controllers;

use App\Models\master_satker;
use App\Http\Requests\StoreMaster_satkerRequest;
use App\Http\Requests\UpdateMaster_satkerRequest;

class MasterSatkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data master satker dari database
        $satkers = master_satker::orderBy('kode_satker', 'asc')->get();
        dd:
        $satkers;
        // Kirim data ke view
        return view('prov.master-satker.index', compact('satkers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storemaster_satkerRequest $request)
    {
        $request->validate([
            'kode_satker' => 'required|string|max:10|unique:master_satker,kode_satker',
            'nama_satker' => 'required|string|max:255',
        ]);

        master_satker::create([
            'kode_satker' => $request->kode_satker,
            'nama_satker' => $request->nama_satker,
        ]);

        return redirect()->route('master-satker.index')->with('success', 'Satker berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(master_satker $master_satker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(master_satker $master_satker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatemaster_satkerRequest $request, Master_satker $master_satker)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(master_satker $master_satker)
    {
        try {
            // Menghapus data satker berdasarkan kode_satker
            $master_satker->delete();

            // Set flash message untuk berhasil
            return redirect()->route('master-satker.index')->with('status', 'success');
        } catch (\Exception $e) {
            // Set flash message untuk gagal
            return redirect()->route('master-satker.index')->with('status', 'error');
        }
    }
}
