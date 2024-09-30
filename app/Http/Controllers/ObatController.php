<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    // Menampilkan daftar obat
    public function index()
    {
        $obat = Obat::all(); // Mengambil semua data obat
        return view('obat.index', compact('obat')); // Mengembalikan view dengan data obat
    }

    // Menampilkan form untuk membuat obat baru
    public function create()
    {
        return view('obat.create'); // Mengembalikan view untuk membuat obat baru
    }

    // Menyimpan data obat baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'dosis' => 'required|string|max:255',
            'jenis' => 'required|in:tablet,kapsul,botol,dus',
            'jumlah' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
        ]);

        Obat::create($request->all()); // Membuat obat baru

        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan.'); // Redirect ke halaman daftar obat
    }


    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id); // Mencari obat berdasarkan ID
        return view('obat.edit', compact('obat')); // Mengembalikan view untuk mengedit obat
    }


    public function update(Request $request, string $id)
    {
        $obat = Obat::findOrFail($id); // Mencari obat berdasarkan ID

        $request->validate([
            'nama_obat' => 'required|string|max:255',
            'dosis' => 'required|string|max:255',
            'jenis' => 'required|in:tablet,kapsul,botol,dus',
            'jumlah' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
        ]);

        $obat->update($request->all()); // Memperbarui data obat

        return redirect()->route('obat.index')->with('success', 'Obat berhasil diperbarui.'); // Redirect ke halaman daftar obat
    }


    // Menghapus data obat
    public function destroy(string $id)
    {
        $obat = Obat::find($id);
        $obat->delete();
        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus');
    }
}
