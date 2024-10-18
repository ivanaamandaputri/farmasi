<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function showOrders()
    {
        $tanggalTransaksi = Transaksi::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->havingRaw('COUNT(*) > 0')
            ->pluck('date');

        // Ambil semua transaksi untuk ditampilkan
        $transaksi = Transaksi::with(['obat', 'user'])->orderBy('created_at', 'asc')->get();

        return view('pengajuan.order', compact('tanggalTransaksi', 'transaksi'));
    }

    // Mendapatkan transaksi dengan AJAX berdasarkan tanggal
    public function getTransaksiByDate(Request $request)
    {
        // Mendapatkan input date dari request
        $date = $request->input('date');

        // Mengambil transaksi berdasarkan tanggal dan relasinya
        $transaksi = Transaksi::with(['obat', 'user'])
            ->whereDate('created_at', $date)
            ->get();

        // Mengirimkan data sebagai JSON
        return response()->json($transaksi);
    }
}
