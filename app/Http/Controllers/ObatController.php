<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    // Menampilkan daftar obat
    public function index()
    {
        // Mengambil semua data obat dan urutkan berdasarkan nama obat dan tanggal pembuatan
        $obat = Obat::orderBy('nama_obat', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $batasMinimum = 5; // Batas minimum stok

        // Cek stok obat yang di bawah atau sama dengan batas minimum
        foreach ($obat as $ob) {
            $ob->warning = $ob->stok <= $batasMinimum; // Tambahkan property 'warning' jika stok hampir habis
        }

        // Cek level user dan kembalikan data ke view yang sesuai
        $readOnly = auth()->user()->level === 'operator'; // true jika operator, false jika admin
        return view('obat.index', compact('obat', 'readOnly')); // Mengembalikan view dengan data obat
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
            'jenis' => 'required|in:tablet,botol,dus',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
        ]);

        // Cek jika kombinasi nama_obat, dosis, dan jenis sudah ada
        $existingObat = Obat::where('nama_obat', $request->nama_obat)
            ->where('dosis', $request->dosis)
            ->where('jenis', $request->jenis)
            ->first();

        if ($existingObat) {
            return redirect()->back()->withErrors(['error' => 'Data obat yang sama sudah ada'])->withInput();
        }

        Obat::create($request->all()); // Membuat obat baru

        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan'); // Redirect ke halaman daftar obat
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
            'jenis' => 'required|in:tablet,botol,dus',
            'stok' => 'required|integer|min:0',
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

    public function show(string $id)
    {
        $obat = Obat::findOrFail($id); // Mencari obat berdasarkan ID
        return view('obat.show', compact('obat')); // Mengembalikan view untuk detail obat
    }

    // Menampilkan data obat untuk operator
    public function operatorIndex()
    {
        $obat = Obat::orderBy('nama_obat', 'asc')->get();
        return view('operator.dataobat', compact('obat')); // Pastikan untuk mengarahkan ke view yang tepat
    }
}
