<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Menghitung jumlah masing-masing model
        $jumlahObat = Obat::count();
        $jumlahTransaksi = Transaksi::count();
        $jumlahUser = User::count();

        return view('dashboard', compact(
            'jumlahObat',
            'jumlahTransaksi',
            'jumlahUser'
        ));
    }
}
