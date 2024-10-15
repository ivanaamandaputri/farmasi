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
        $total = $obat->harga * $request->jumlah;

        // Periksa stok obat
        if ($request->jumlah > $obat->stok) {
            return back()->withErrors(['jumlah' => 'Jumlah melebihi stok yang tersedia.'])->withInput();
        }

        // Simpan transaksi baru
        Transaksi::create([
            'obat_id' => $request->obat_id,
            'user_id' => Auth::id(),
            'jumlah' => $request->jumlah,
            'total' => $total,
            'status' => 'pending',
        ]);

        // Kurangi stok obat
        $obat->decrement('stok', $request->jumlah);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
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
            return back()->withErrors(['jumlah' => 'Jumlah melebihi stok yang tersedia.'])->withInput();
        }

        // Update transaksi
        $transaksi->update([
            'obat_id' => $request->obat_id,
            'jumlah' => $request->jumlah,
            'total' => $total,
        ]);

        // Update stok obat
        $obat->increment('stok', $transaksi->jumlah); // Kembalikan stok lama
        $obat->decrement('stok', $request->jumlah); // Kurangi stok baru

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi)
    {
        $obat = $transaksi->obat;

        // Kembalikan stok obat
        $obat->increment('stok', $transaksi->jumlah);
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function showOrders()
    {
        // Mengambil tanggal transaksi
        $tanggalTransaksi = Transaksi::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->havingRaw('COUNT(*) > 0') // Hanya ambil tanggal dengan transaksi
            ->pluck('date');

        return view('pengajuan.order', compact('tanggalTransaksi'));
    }

    public function getByDate($date)
    {
        $transaksiPerTanggal = Transaksi::whereDate('created_at', $date)->with(['obat', 'user'])->get();
        if ($transaksiPerTanggal->isEmpty()) {
            return redirect()->route('transaksi.index')->with('info', 'Tidak ada transaksi untuk tanggal ini.');
        }

        return view('transaksi.index', [
            'transaksi' => $transaksiPerTanggal,
            'selectedDate' => $date,
        ]);
    }
    public function approve($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = 'disetujui';
        $transaksi->save();

        // Kurangi stok obat
        $transaksi->obat->decrement('stok', $transaksi->jumlah);

        return redirect()->back()->with('success', 'Transaksi disetujui dan stok berkurang.');
    }

    public function reject(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = 'ditolak';
        $transaksi->alasan_penolakan = $request->alasan;
        $transaksi->save();
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        return redirect()->back()->with('success', 'Transaksi ditolak.');
    }
}
