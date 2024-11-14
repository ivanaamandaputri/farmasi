<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    /**
     * Menampilkan semua transaksi, dikelompokkan berdasarkan tanggal.
     */
    public function showOrders()
    {
        $transaksi = Transaksi::with(['obat', 'user'])
            ->orderBy('tanggal', 'desc')
            ->get();

        $groupedTransaksi = $transaksi->groupBy('tanggal')
            ->map(function ($transactions) {
                return $transactions->groupBy('user.ruangan');
            });

        return view('pengajuan.index', compact('groupedTransaksi'));
    }

    /**
     * Menampilkan semua transaksi untuk admin.
     */
    public function showTransactions()
    {
        $transaksiGroup = Transaksi::with(['obat', 'user'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('transaksi.index', compact('transaksiGroup'));
    }

    /**
     * Menyetujui transaksi.
     */
    public function approve(Request $request, $id)
    {
        try {
            // Mencari transaksi berdasarkan ID
            $transaksi = Transaksi::findOrFail($id);

            // Pastikan data ACC ada dalam request
            $acc = $request->input('acc');

            if (!$acc || $acc <= 0) {
                return response()->json(['error' => 'Jumlah ACC tidak valid.'], 400);
            }

            // Pastikan jumlah ACC tidak melebihi jumlah yang diminta
            if ($acc > $transaksi->jumlah) {
                return response()->json(['error' => 'Jumlah ACC tidak boleh lebih besar dari jumlah permintaan.'], 400);
            }

            // Proses transaksi dan update data
            $total = $acc * $transaksi->harga; // Menghitung total berdasarkan ACC * harga
            $transaksi->acc = $acc;
            $transaksi->status = 'Disetujui';
            $transaksi->total = $total; // Menyimpan total yang sudah dihitung
            $transaksi->save();

            // Update stok jika diperlukan
            $obat = $transaksi->obat;
            $obat->stok -= $acc;  // Mengurangi stok sesuai jumlah ACC yang disetujui
            $obat->save();

            // Opsional: Log atau notifikasi disetujui
            // Log::info("Transaksi $transaksi->id disetujui dengan ACC: $acc");

            return response()->json(['message' => 'Transaksi disetujui dan stok berkurang.'], 200);
        } catch (\Exception $e) {
            // Menangani error jika terjadi
            // Log::error("Terjadi kesalahan saat memproses transaksi: " . $e->getMessage());

            return response()->json(['error' => 'Terjadi kesalahan saat memproses transaksi.'], 500);
        }
    }



    public function reject(Request $request, $id)
    {
        // Validasi alasan penolakan
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        try {
            // Temukan transaksi berdasarkan ID
            $transaksi = Transaksi::findOrFail($id);

            // Periksa apakah transaksi sudah ditolak sebelumnya
            if ($transaksi->status === 'Ditolak') {
                return response()->json(['error' => 'Transaksi sudah ditolak.'], 400);
            }

            // Perbarui status transaksi menjadi 'Ditolak' dan simpan alasan penolakan
            $transaksi->update([
                'status' => 'Ditolak',
                'alasan_penolakan' => $request->input('reason'),
            ]);

            // Optionally, log the rejection or notify users here
            // Log::info("Transaksi $transaksi->id ditolak. Alasan: " . $request->input('reason'));

            return response()->json(['success' => 'Transaksi berhasil ditolak.'], 200);
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            // Log::error("Terjadi kesalahan saat menolak transaksi: " . $e->getMessage());

            return response()->json(['error' => 'Terjadi kesalahan saat memproses penolakan transaksi.'], 500);
        }
    }
}
