<?php

namespace App\Http\Controllers;

use App\Models\master_komoditas;
use Illuminate\Http\Request;
use App\Models\MasterKomUtama;

class MasterKomoditasController extends Controller
{
    //BUAT KOMODITAS BIASA
    public function index(Request $request)
    {
        $search = $request->input('search'); // Ambil input pencarian

        $komoditas = master_komoditas::query()
            ->when($search, function ($query, $search) {
                return $query->where('kode_kom', 'like', "%{$search}%")
                    ->orWhere('nama_kom', 'like', "%{$search}%");
            })
            ->orderBy('kode_kom', 'asc')
            ->paginate(10);

        return view('prov.master-komoditas.index', compact('komoditas', 'search'));
    }

    public function create()
    {
        $kelompok = master_komoditas::where('flag', 1)->get();
        $subKelompok = master_komoditas::where('flag', 2)->get();
        return view('prov.master-komoditas.create', compact('kelompok', 'subKelompok'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'flag' => 'required|in:1,2,3',
            'kode' => 'required|unique:master_komoditas,kode_kom',
            'nama' => 'required|unique:master_komoditas,nama_kom',
        ], [
            'kode.unique' => 'Kode komoditas sudah digunakan, silahkan pilih kode lain.',
            'nama.unique' => 'Nama komoditas sudah digunakan, silahkan pilih kode lain.',
        ]);

        master_komoditas::create([
            'flag' => $request->flag,
            'kode_kom' => strtoupper($request->kode),
            'nama_kom' => strtoupper($request->nama),
            'parent_id' => $request->parent_id ?? null,
        ]);

        return redirect()->route('prov.master-komoditas.index')->with('success', 'Komoditas berhasil ditambahkan.');
    }


    public function edit($kode_kom)
    {
        $komoditas = master_komoditas::where('kode_kom', $kode_kom)->firstOrFail();

        return response()->json([
            'kode_kom' => $komoditas->kode_kom,
            'nama_kom' => $komoditas->nama_kom,
        ]);
    }

    // Update data komoditas
    public function update(Request $request, $kode_kom)
    {
        $komoditas = master_komoditas::where('kode_kom', $kode_kom)->firstOrFail();

        $request->validate([
            'kode_kom' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($kode_kom) {
                    $expectedLength = strlen($kode_kom);
                    if (strlen($value) !== $expectedLength) {
                        $fail("Kode komoditas harus memiliki panjang $expectedLength digit.");
                    }
                }
            ],
            'nama_kom' => 'required|string|max:255',
        ]);

        $komoditas->kode_kom = $request->kode_kom;
        $komoditas->nama_kom = $request->nama_kom;
        $komoditas->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('master-komoditas.index')->with('success', 'Data komoditas berhasil diperbarui.');
    }


    public function destroy(master_komoditas $komoditas)
    {
        $komoditas->delete();
        return redirect()->route('master-komoditas.index')->with('success', 'Komoditas berhasil dihapus.');
    }

    //BUAT KOMODITAS UTAMA
    public function indexKomUtama(Request $request)
    {
        $search = $request->input('search');
        $komoditasUtama = MasterKomUtama::query()
            ->when($search, function ($query, $search) {
                return $query->where('kode_kom', 'like', "%{$search}%")
                    ->orWhere('nama_kom', 'like', "%{$search}%");
            })
            ->orderBy('kode_kom', 'asc')
            ->paginate(10);

        return view('prov.komoditas-utama.index', compact('komoditasUtama', 'search'));
    }

    // Simpan komoditas utama (multi pilih)
    public function storeKomUtama(Request $request)
    {
        $request->validate([
            'komoditas' => 'required|exists:master_komoditas,kode_kom',
        ]);

        // Cek apakah sudah ada di master_kom_utama
        if (MasterKomUtama::where('kode_kom', $request->komoditas)->exists()) {
            return redirect()->route('komoditas-utama.index')
                ->with('error_kom_utama', 'Komoditas utama sudah ada!');
        }

        $komoditas = master_komoditas::where('kode_kom', $request->komoditas)->first();

        if ($komoditas) {
            MasterKomUtama::create([
                'kode_kom' => $komoditas->kode_kom,
                'nama_kom' => $komoditas->nama_kom
            ]);
        }

        return redirect()->route('komoditas-utama.index')->with('success', 'Komoditas utama berhasil disimpan.');
    }

    // Autocomplete search komoditas utama
    public function searchKomUtama(Request $request)
    {
        $query = $request->input('q');
        $results = master_komoditas::where('nama_kom', 'like', "%{$query}%")
            ->orWhere('kode_kom', 'like', "%{$query}%")
            ->limit(10)
            ->get(['kode_kom', 'nama_kom']);
        return response()->json($results);
    }

    // Edit komoditas utama (return data JSON)
    public function editKomUtama($kode_kom)
    {
        $komoditas = MasterKomUtama::where('kode_kom', $kode_kom)->firstOrFail();
        return response()->json([
            'kode_kom' => $komoditas->kode_kom,
            'nama_kom' => $komoditas->nama_kom,
        ]);
    }

    // Update komoditas utama
    public function updateKomUtama(Request $request, $kode_kom)
    {
        $request->validate([
            'kode_kom' => 'required|exists:master_komoditas,kode_kom',
        ]);

        // Cek jika kode_kom baru sudah ada di master_kom_utama (dan bukan yang sedang diedit)
        if (MasterKomUtama::where('kode_kom', $request->kode_kom)->where('kode_kom', '!=', $kode_kom)->exists()) {
            return redirect()->route('komoditas-utama.index')
                ->with('error_kom_utama', 'Komoditas utama sudah ada!');
        }

        $komoditas = master_komoditas::where('kode_kom', $request->kode_kom)->first();
        $utama = MasterKomUtama::where('kode_kom', $kode_kom)->firstOrFail();

        if ($komoditas) {
            $utama->kode_kom = $komoditas->kode_kom;
            $utama->nama_kom = $komoditas->nama_kom;
            $utama->save();
        }

        return redirect()->route('komoditas-utama.index')->with('success', 'Komoditas utama berhasil diperbarui.');
    }

    // Hapus komoditas utama
    public function destroyKomUtama($kode_kom)
    {
        $komoditas = MasterKomUtama::where('kode_kom', $kode_kom)->firstOrFail();
        $komoditas->delete();
        return redirect()->route('komoditas-utama.index')->with('success', 'Komoditas utama berhasil dihapus.');
    }
}
