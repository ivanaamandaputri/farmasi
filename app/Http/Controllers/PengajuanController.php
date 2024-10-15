<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    // Menampilkan data pesanan
    public function showOrders()
    {
        // Mengambil tanggal transaksi dan mengelompokkan berdasarkan tanggal
        $tanggalTransaksi = Transaksi::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->havingRaw('COUNT(*) > 0')
            ->pluck('date');

        return view('pengajuan.order', compact('tanggalTransaksi'));
    }

    // Mendapatkan transaksi dengan AJAX berdasarkan tanggal
    public function getTransaksiByDate(Request $request)
    {
        $date = $request->input('date');

        // Mengambil transaksi berdasarkan tanggal dan relasinya
        $transaksi = Transaksi::with(['obat', 'user'])
            ->whereDate('created_at', $date)
            ->get();

        // Mengirimkan data sebagai JSON
        return response()->json($transaksi);
    }
}
