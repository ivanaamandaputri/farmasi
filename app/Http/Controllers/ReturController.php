<?php

namespace App\Http\Controllers;

use App\Models\Retur;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReturController extends Controller
{

    public function index($transaksiId = null)
    {
        if ($transaksiId) {
            // Mengambil semua retur untuk transaksi tertentu
            $returs = Retur::where('transaksi_id', $transaksiId)->get();
        } else {
            $returs = Retur::all(); // Mengambil semua data retur jika tidak ada ID transaksi
        }

        return view('retur.index', compact('returs', 'transaksiId')); // Mengirim data ke view
    }


    public function create()
    {
        return view('retur.create'); // Menampilkan form untuk menambah retur
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'jumlah' => 'required|integer',
            'alasan_retur' => 'required|string',
            'password' => 'required|string',
        ]);

        // Verifikasi password
        if (Hash::check($request->password, auth()->user()->password)) {
            // Membuat data retur baru
            Retur::create([
                'transaksi_id' => $request->transaksi_id,
                'jumlah' => $request->jumlah,
                'alasan_retur' => $request->alasan_retur,
                'user_id' => auth()->user()->id, // Pastikan user yang melakukan retur
                'status' => 'Diretur', // Mengubah status menjadi 'Diretur'
            ]);

            // Update status transaksi jika diperlukan
            $transaksi = Transaksi::find($request->transaksi_id);
            $transaksi->status = 'Diretur';
            $transaksi->save();

            return redirect()->route('retur.index')->with('success', 'Retur berhasil ditambahkan.');
        } else {
            return back()->withErrors(['password' => 'Password salah.']);
        }
    }

    public function show(Retur $retur)
    {
        return view('retur.show', compact('retur')); // Menampilkan detail retur
    }


    public function edit(Retur $retur)
    {
        return view('retur.edit', compact('retur')); // Menampilkan form untuk mengedit retur
    }


    public function update(Request $request, Retur $retur)
    {
        // Validasi data
        $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'obat_id' => 'required|exists:obat,id',
            'user_id' => 'required|exists:user,id',
            'jumlah' => 'required|integer',
            'alasan_retur' => 'nullable|string',
            'status' => 'nullable|string',
        ]);


        // Update data
        $retur->update($request->all());

        return redirect()->route('retur.index')->with('success', 'Data berhasil diperbarui.');
    }


    public function destroy(Retur $retur)
    {
        $retur->delete(); // Menghapus data retur
        return redirect()->route('retur.index')->with('success', 'Retur berhasil dihapus.');
    }
}
