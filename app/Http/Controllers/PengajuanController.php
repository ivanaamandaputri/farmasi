<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        // dd($groupedTransaksi);
        // $notifikasi = Notifikasi::all();

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

            // Validasi input
            $request->validate([
                'acc' => 'required|integer|min:1|max:' . $transaksi->jumlah, // Jumlah ACC minimal 1 dan tidak melebihi jumlah permintaan
            ]);

            $acc = $request->input('acc'); // Mengambil jumlah ACC
            $obat = $transaksi->obat; // Mengambil data obat terkait

            // Log untuk debug
            Log::info('Jumlah ACC: ' . $acc);
            Log::info('Harga Obat: ' . $obat->harga);

            // Periksa stok obat
            if ($obat->stok < $acc) {
                return response()->json(['error' => 'Stok obat tidak mencukupi.'], 400);
            }
            if ($acc > $obat->stok) {
                return response()->json(['error' => 'Jumlah ACC melebihi stok yang tersedia.'], 400);
            }

            // Proses transaksi dan update data
            $total = $acc * $obat->harga; // Menghitung total berdasarkan ACC * harga obat
            Log::info('Total yang dihitung: ' . $total);

            // Update transaksi
            $transaksi->update([
                'acc' => $acc,
                'status' => 'Disetujui',
                'total' => $total,  // Pastikan total dihitung dengan benar
            ]);

            // Update stok obat
            $obat->stok -= $acc;  // Mengurangi stok sesuai jumlah ACC yang disetujui
            $obat->save();

            // Kirim notifikasi ke operator
            Notifikasi::create([
                'user_id' => $transaksi->user_id, // ID operator yang mengajukan
                'judul' => 'Pengajuan Disetujui',
                'pesan' => 'Pengajuan Anda telah disetujui.',
                'level' => 'operator', // Level notifikasi
            ]);

            return response()->json(['message' => 'Transaksi disetujui dan stok berkurang.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        // Validasi alasan penolakan
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);
        $reason = $request->input('reason');
        try {
            // Temukan transaksi berdasarkan ID
            $transaksi = Transaksi::findOrFail($id);

            // Periksa apakah transaksi sudah ditolak sebelumnya
            if ($transaksi->status === 'Ditolak') {
                return response()->json(['error' => 'Transaksi sudah ditolak.'], 400);
            }
            if (in_array($transaksi->status, ['Ditolak', 'Disetujui'])) {
                return response()->json(['error' => 'Transaksi sudah diproses sebelumnya.'], 400);
            }


            // Perbarui status transaksi menjadi 'Ditolak' dan simpan alasan penolakan
            $transaksi->update([
                'status' => 'Ditolak',
                'alasan_penolakan' => $request->input('reason'),
            ]);

            // Kirim notifikasi ke operator
            Notifikasi::create([
                'user_id' => $transaksi->user_id, // ID operator yang mengajukan
                'judul' => 'Pengajuan Ditolak',
                'pesan' => 'Pengajuan Anda ditolak dengan alasan: ' . $reason,
                'level' => 'operator', // Level notifikasi
            ]);
            return response()->json(['success' => 'Transaksi berhasil ditolak.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat memproses penolakan transaksi.'], 500);
        }
    }

    public function getNotifikasi()
    {
        $level = auth()->user()->level;

        if ($level === 'admin') {
            $notifikasi = Notifikasi::where('level', 'admin')
                ->where('dibaca', false)
                ->get();
        } elseif ($level === 'operator') {
            $notifikasi = Notifikasi::where('level', 'operator')
                ->where('user_id', auth()->id())
                ->where('dibaca', false)
                ->get();
        } else {
            abort(403, 'Unauthorized access.');
        }
        // dd($notifikasi);
        // Pastikan variabel notifikasi dikirim ke tampilan
        return view('dashboard', compact('notifikasi'));
    }

    public function bacaNotifikasi($id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        $notifikasi->update(['dibaca' => true]);

        return redirect()->back();
    }
}
