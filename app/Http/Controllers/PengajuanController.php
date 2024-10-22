<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function showOrders()
    {
        // Mengambil tanggal transaksi
        $tanggalTransaksi = Transaksi::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->havingRaw('COUNT(*) > 0') // Hanya ambil tanggal dengan transaksi
            ->pluck('date');

        // Ambil transaksi yang sesuai untuk ditampilkan dan urutkan dari yang terbaru
        $transaksi = Transaksi::with(['obat', 'user'])
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal terbaru
            ->get();

        return view('pengajuan.index', compact('tanggalTransaksi', 'transaksi'));
    }



    public function getByDate($date)
    {
        $transaksiPerTanggal = Transaksi::whereDate('created_at', $date)->with(['obat', 'user'])->get();
        if ($transaksiPerTanggal->isEmpty()) {
            return redirect()->route('transaksi.index')->with('info', 'Tidak ada transaksi untuk tanggal ini');
        }

        return view('transaksi.index', [
            'transaksi' => $transaksiPerTanggal,
            'selectedDate' => $date,
        ]);
    }

    public function approve($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $obat = $transaksi->obat;

        // Pastikan stok mencukupi sebelum disetujui
        if ($transaksi->jumlah > $obat->stok) {
            return redirect()->back()->withErrors(['jumlah' => 'Stok tidak cukup untuk menyetujui transaksi ini']);
        }

        // Cek apakah transaksi sudah disetujui
        if ($transaksi->status === 'Disetujui') {
            return redirect()->back()->withErrors(['error' => 'Transaksi ini sudah disetujui']);
        }

        // Set status transaksi menjadi disetujui
        $transaksi->status = 'Disetujui';
        $transaksi->save();

        // Kurangi stok obat
        $obat->decrement('stok', $transaksi->jumlah);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi disetujui dan stok berkurang');
    }

    public function reject(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Cek apakah transaksi sudah disetujui
        if ($transaksi->status === 'Disetujui') {
            return redirect()->back()->withErrors(['error' => 'Transaksi ini sudah disetujui']);
        }

        // Set status transaksi menjadi ditolak
        $transaksi->status = 'Ditolak';
        $transaksi->alasan_penolakan = $request->alasan; // Simpan alasan penolakan
        $transaksi->save();

        return response()->json(['success' => 'Transaksi berhasil ditolak']);
    }
}
