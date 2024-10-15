<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        // Ambil data transaksi bulan ini
        $laporanTransaksi = Transaksi::with('obat', 'user')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->orderBy('created_at', 'desc') // Mengurutkan data berdasarkan tanggal pembuatan, yang terbaru di atas
            ->get();

        return view('laporan.index', compact('laporanTransaksi'));
    }

    public function download()
    {
        // Ambil data transaksi bulan ini
        $laporanTransaksi = Transaksi::with('obat', 'user')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->get();

        // Generate PDF
        $pdf = PDF::loadView('laporan.pdf', compact('laporanTransaksi'));
        return $pdf->download('laporan_bulanan.pdf');
    }
}
