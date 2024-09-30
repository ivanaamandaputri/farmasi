<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Obat;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    // Menampilkan daftar transaksi
    public function index()
    {
        $transaksi = Transaksi::with(['obat', 'user'])->get();
        return view('transaksi.index', compact('transaksi'));
    }

    // Menampilkan form untuk membuat transaksi baru
    public function create()
    {
        $obat = Obat::orderBy('nama_obat', 'asc')->get();
        $user = User::orderBy('nama_pegawai', 'asc')->get();
        return view('transaksi.create', compact('obat', 'user'));
    }

    // Menyimpan data transaksi baru
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_obat' => 'required|string',
            'dosis' => 'required|string',
            'jenis' => 'required|in:tablet,kapsul,botol,dus',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'nama_pemesan' => 'required|string',
            'ruangan' => 'required|in:Instalasi Farmasi,puskesmas Kaligangsa,puskesmas Margadana,puskesmas Tegal Barat,puskesmas Debong Lor,puskesmas Tegal Timur,puskesmas Slerok,puskesmas Tegal Selatan,puskesmas Bandung',

        ]);

        // Menyimpan data transaksi
        Transaksi::create($request->all());

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan');
    }


    // Menampilkan form untuk mengedit data transaksi
    public function edit(Transaksi $transaksi)
    {
        $obat = Obat::orderBy('nama_obat', 'asc')->get();
        $user = User::orderBy('nama_pegawai', 'asc')->get();
        return view('transaksi.edit', compact('transaksi', 'obat', 'user'));
    }

    // Memperbarui data transaksi
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'dosis' => 'required|string',
            'jenis' => 'required|in:tablet,kapsul,botol,dus',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'nama_pemesan' => 'required|string',
            'ruangan' => 'required|in:Instalasi Farmasi,puskesmas Kaligangsa,puskesmas Margadana,puskesmas Tegal Barat,puskesmas Debong Lor,puskesmas Tegal Timur,puskesmas Slerok,puskesmas Tegal Selatan,puskesmas Bandung',

        ]);

        $transaksi->update($request->all());

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    // Menghapus data transaksi
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    // Mencetak transaksi
    public function print($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $pdf = Pdf::loadView('transaksi.print', compact('transaksi'));
        return $pdf->download('transaksi_' . $transaksi->id . '.pdf');
    }
}
