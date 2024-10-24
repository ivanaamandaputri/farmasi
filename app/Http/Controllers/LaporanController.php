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

        return view('laporan.index', compact('laporanTransaksi')); // Pastikan ini
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


    public function obatMasuk(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun ?? $request->tahun_per;

        $transaksiObatMasuk = Transaksi::where('jenis_transaksi', 'masuk')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->where('status', 'disetujui')
            ->get();

        return view('laporan.obatMasuk', compact('transaksiObatMasuk'));
    }

    public function obatKeluar(Request $request)
    {
        $bulan = $request->bulan_keluar;
        $tahun = $request->tahun_keluar ?? $request->tahun_keluar_per;

        $transaksiObatKeluar = Transaksi::where('jenis_transaksi', 'keluar')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->where('status', 'disetujui')
            ->get();

        return view('laporan.obatKeluar', compact('transaksiObatKeluar'));
    }
}
