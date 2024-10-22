<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        // Mengambil transaksi berdasarkan user yang login
        $transaksi = Transaksi::with(['obat', 'user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $obat = Obat::all();
        return view('transaksi.create', compact('obat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $obat = Obat::find($request->obat_id);

        // Periksa stok obat
        if ($request->jumlah > $obat->stok) {
            return back()->withErrors(['jumlah' => 'Jumlah melebihi stok yang tersedia'])->withInput();
        }

        // Simpan transaksi baru
        Transaksi::create([
            'obat_id' => $request->obat_id,
            'user_id' => Auth::id(),
            'jumlah' => $request->jumlah,
            'total' => $obat->harga * $request->jumlah,
            'status' => 'pending',
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan dan menunggu persetujuan');
    }

    public function edit(Transaksi $transaksi)
    {
        $obat = Obat::all();
        return view('transaksi.edit', compact('transaksi', 'obat'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $obat = Obat::find($request->obat_id);
        $total = $obat->harga * $request->jumlah;

        // Hitung selisih jumlah obat
        $selisih = $request->jumlah - $transaksi->jumlah;

        // Periksa apakah stok mencukupi
        if ($selisih > 0 && $selisih > $obat->stok) {
            return back()->withErrors(['jumlah' => 'Jumlah melebihi stok yang tersedia'])->withInput();
        }

        // Update transaksi
        $transaksi->update([
            'obat_id' => $request->obat_id,
            'jumlah' => $request->jumlah,
            'total' => $total,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy(Transaksi $transaksi)
    {
        $obat = $transaksi->obat;

        // Kembalikan stok obat
        $obat->increment('stok', $transaksi->jumlah);
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');
    }


    public function approve(Request $request, $id)
    {
        // Mencari transaksi berdasarkan ID
        $transaksi = Transaksi::findOrFail($id);
        $obat = $transaksi->obat;

        // Pastikan stok mencukupi sebelum disetujui
        if ($transaksi->jumlah > $obat->stok) {
            return response()->json(['error' => 'Stok tidak cukup untuk menyetujui transaksi ini.'], 400);
        }

        // Cek apakah transaksi sudah disetujui
        if ($transaksi->status === 'Disetujui') {
            return response()->json(['error' => 'Transaksi ini sudah disetujui.'], 400);
        }

        // Set status transaksi menjadi disetujui
        $transaksi->status = 'Disetujui';
        $transaksi->save();

        // Kurangi stok obat
        $obat->decrement('stok', $transaksi->jumlah);

        return response()->json(['message' => 'Transaksi disetujui dan stok berkurang.']);
    }

    public function reject(Request $request, $id)

    {
        // Validasi data yang diterima
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        // Cari transaksi berdasarkan ID
        $transaksi = Transaksi::findOrFail($id);

        // Perbarui status dan alasan penolakan
        $transaksi->status = 'Ditolak';
        $transaksi->alasan_penolakan = $request->alasan;
        $transaksi->save();

        return response()->json(['message' => 'Transaksi berhasil ditolak.']);
    }
}
