<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $laporanTransaksi = Transaksi::with(['obat', 'user'])->get();
        // Query untuk mengambil data transaksi dengan filter status
        $query = Transaksi::with('obat', 'user')
            ->whereIn('status', ['selesai', 'disetujui', 'retur']); // Status yang diambil

        // Filter berdasarkan bulan
        if ($request->has('bulan') && $request->bulan != '') {
            $query->whereMonth('tanggal', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->has('tahun') && $request->tahun != '') {
            $query->whereYear('tanggal', $request->tahun);
        }

        // Filter berdasarkan instansi (ruangan)
        if ($request->has('ruangan') && $request->ruangan != '') {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('ruangan', $request->ruangan);
            });
        }

        // Filter berdasarkan obat
        if ($request->has('obat_id') && $request->obat_id != '') {
            $query->where('obat_id', $request->obat_id);
        }

        // Ambil data transaksi yang sudah difilter dengan pagination
        $laporanTransaksi = $query->orderBy('tanggal', 'asc')->paginate(10);

        // Ambil daftar obat untuk filter
        $obatList = Obat::all();

        // Daftar instansi yang tersedia
        $instansiList = [
            'Instalasi Farmasi',
            'Puskesmas Kaligangsa',
            'Puskesmas Margadana',
            'Puskesmas Tegal Barat',
            'Puskesmas Debong Lor',
            'Puskesmas Tegal Timur',
            'Puskesmas Slerok',
            'Puskesmas Tegal Selatan',
            'Puskesmas Bandung',
        ];

        // Kirim data ke view
        return view('laporan.index', compact('laporanTransaksi', 'obatList', 'instansiList'));
    }

    public function cetak(Request $request)
    {
        // Query untuk mengambil data transaksi dengan filter status
        $query = Transaksi::with('obat', 'user')
            ->whereIn('status', ['selesai', 'disetujui', 'retur']); // Status yang diambil

        // Filter berdasarkan bulan
        if ($request->has('bulan') && $request->bulan != '') {
            $query->whereMonth('tanggal', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->has('tahun') && $request->tahun != '') {
            $query->whereYear('tanggal', $request->tahun);
        }

        // Filter berdasarkan instansi (ruangan)
        if ($request->has('ruangan') && $request->ruangan != '') {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('ruangan', $request->ruangan);
            });
        }

        // Filter berdasarkan obat
        if ($request->has('obat_id') && $request->obat_id != '') {
            $query->where('obat_id', $request->obat_id);
        }

        // Ambil data transaksi yang sudah difilter tanpa paginasi untuk cetak
        $laporanTransaksi = $query->orderBy('tanggal', 'asc')->get();

        // Kirim data ke view untuk cetak
        return view('laporan.cetak', compact('laporanTransaksi'));
    }
}
