<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\Retur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TransaksiController extends Controller
{
    // Menampilkan daftar transaksi
    public function index()
    {
        // Mengambil transaksi yang terkait dengan pengguna yang sedang login
        $transaksi = Transaksi::with(['obat', 'user'])
            ->where('user_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->get();

        // Kelompokkan transaksi berdasarkan tanggal
        $groupedTransaksi = $transaksi->groupBy(function ($item) {
            return $item->tanggal; // Pastikan 'tanggal' adalah atribut valid
        });

        // Kirimkan data ke view
        return view('transaksi.index', compact('groupedTransaksi'));
    }

    // Menampilkan form untuk membuat transaksi baru
    public function create()
    {
        // Mengambil semua obat untuk ditampilkan di form
        $obat = Obat::all();
        return view('transaksi.create', compact('obat'));
    }

    // Menyimpan transaksi baru
    public function store(Request $request)
    {
        // Validasi data input dari form
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        // Mencari obat berdasarkan ID
        $obat = Obat::find($request->obat_id);

        // Memeriksa apakah jumlah yang diminta melebihi stok
        if ($request->jumlah > $obat->stok) {
            return back()->withErrors(['jumlah' => 'Jumlah melebihi stok yang tersedia'])->withInput();
        }

        // Menyimpan transaksi baru
        Transaksi::create([
            'obat_id' => $request->obat_id,
            'user_id' => Auth::id(),
            'jumlah' => $request->jumlah,
            'total' => $obat->harga * $request->jumlah,
            'status' => 'Menunggu',
            'tanggal' => $request->tanggal,
            'jenis_obat_id' => $obat->jenis_obat_id,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan dan menunggu persetujuan');
    }

    // Menampilkan form untuk mengedit transaksi
    public function edit(Transaksi $transaksi)
    {
        // Mengambil semua obat untuk ditampilkan di form edit
        $obat = Obat::all();
        return view('transaksi.edit', compact('transaksi', 'obat'));
    }

    // Memperbarui transaksi yang sudah ada
    public function update(Request $request, Transaksi $transaksi)
    {
        // Validasi data input dari form
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Mencari obat berdasarkan ID
        $obat = Obat::find($request->obat_id);
        $total = $obat->harga * $request->jumlah;

        // Menghitung selisih jumlah obat
        $selisih = $request->jumlah - $transaksi->jumlah;

        // Memeriksa apakah stok mencukupi untuk pembaruan
        if ($selisih > 0 && $selisih > $obat->stok) {
            return back()->withErrors(['jumlah' => 'Jumlah melebihi stok yang tersedia'])->withInput();
        }

        // Memperbarui transaksi
        $transaksi->update([
            'obat_id' => $request->obat_id,
            'jumlah' => $request->jumlah,
            'total' => $total,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui');
    }

    // Menghapus transaksi
    public function destroy(Transaksi $transaksi)
    {
        // Mengembalikan stok obat
        $obat = $transaksi->obat;
        $obat->increment('stok', $transaksi->jumlah);

        // Menghapus transaksi
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');
    }

    // Menyimpan retur transaksi
    public function storeRetur(Request $request)
    {
        // Validasi data input untuk retur
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'jumlah' => 'required|integer',
            'alasan' => 'required|string',
            'password' => 'required|string', // Periksa password sesuai kebutuhan

        ]);

        // Membuat retur baru
        Retur::create([
            'transaksi_id' => $validated['transaksi_id'],
            'jumlah' => $validated['jumlah'],
            'alasan_retur' => $validated['alasan'],
            'password' => $validated['password'],
            'user_id' => Auth::id(), // Ambil user yang sedang login
        ]);

        return response()->json(['success' => true]);
    }

    public function finish($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Periksa status dan ubah jika perlu
        if ($transaksi->status === 'Menunggu') {
            $transaksi->status = 'Selesai';
            $transaksi->save();

            return response()->json(['message' => 'Transaksi berhasil diubah menjadi Selesai']);
        }

        return response()->json(['error' => 'Transaksi tidak bisa diubah'], 400);
    }
}
