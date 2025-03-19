<?php

namespace App\Http\Controllers;

use App\Models\master_komoditas;
use App\Http\Requests\Storemaster_komoditasRequest;
use App\Http\Requests\Updatemaster_komoditasRequest;
use Illuminate\Http\Request;

class MasterKomoditasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $komoditas = master_komoditas::all();
        return view('komoditas.index', compact('komoditas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('komoditas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'flag' => 'required|integer|min:0|max:4',
            'nama_kom' => 'required|string|max:24',
            'kode_kom' => 'required|integer|unique:master_komoditas,kode_kom',
        ]);

        master_komoditas::create($request->all());

        return redirect()->route('komoditas.index')->with('success', 'Komoditas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(master_komoditas $komoditas)
    {
        return view('komoditas.show', compact('komoditas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(master_komoditas $komoditas)
    {
        return view('komoditas.edit', compact('komoditas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, master_komoditas $komoditas)
    {
        $request->validate([
            'flag' => 'required|integer|min:0|max:4',
            'nama_kom' => 'required|string|max:24',
            'kode_kom' => 'required|integer|unique:master_komoditas,kode_kom,' . $komoditas->id,
        ]);

        $komoditas->update($request->all());

        return redirect()->route('komoditas.index')->with('success', 'Komoditas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(master_komoditas $komoditas)
    {
        $komoditas->delete();
        return redirect()->route('komoditas.index')->with('success', 'Komoditas berhasil dihapus.');
    }
}
