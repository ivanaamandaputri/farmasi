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
        // Mencari transaksi berdasarkan ID
        $transaksi = Transaksi::findOrFail($id);
        $obat = $transaksi->obat;

        // Validasi input untuk acc (jumlah yang disetujui)
        $request->validate([
            'acc' => 'required|integer|min:1|max:' . $obat->stok,
        ]);

        // Menyimpan nilai acc dari request
        $acc = $request->input('acc');

        // Memastikan transaksi belum disetujui
        if ($transaksi->status === 'Disetujui') {
            return response()->json(['error' => 'Transaksi sudah disetujui'], 400);
        }

        // Memastikan stok cukup untuk transaksi
        if ($acc > $obat->stok) {
            return response()->json(['error' => 'Stok tidak cukup untuk menyetujui transaksi ini.'], 400);
        }

        // Menggunakan DB transaction untuk menjaga integritas data
        DB::transaction(function () use ($transaksi, $obat, $acc) {
            // Memperbarui status transaksi dan informasi lainnya
            $transaksi->update([
                'total' => $acc * $obat->harga,  // Menghitung total berdasarkan jumlah yang disetujui
                'status' => 'Disetujui',          // Memperbarui status transaksi
                'acc' => $acc,                    // Menyimpan jumlah yang disetujui
            ]);

            // Mengurangi stok obat sesuai jumlah yang disetujui
            $obat->decrement('stok', $acc);
        });

        // Logging informasi transaksi yang disetujui
        Log::info("Transaksi ID {$id} disetujui oleh user ID " . Auth::id());

        // Mengembalikan respon sukses
        return response()->json(['message' => 'Transaksi disetujui dan stok berkurang.']);
    }


    /**
     * Menolak transaksi.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status === 'Ditolak') {
            return response()->json(['error' => 'Transaksi sudah ditolak.'], 400);
        }

        $transaksi->update([
            'status' => 'Ditolak',
            'alasan_penolakan' => $request->reason,
        ]);

        Log::info("Transaksi ID {$id} ditolak oleh user ID " . Auth::id() . " dengan alasan: " . $request->reason);

        return response()->json(['message' => 'Transaksi berhasil ditolak.']);
    }
}
