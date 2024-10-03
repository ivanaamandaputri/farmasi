<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Menghitung jumlah masing-masing model untuk admin
        $jumlahObat = Obat::count();
        $jumlahTransaksi = Transaksi::count();
        $jumlahUser = User::count();

        return view('dashboard', compact('jumlahObat', 'jumlahTransaksi', 'jumlahUser'));
    }

    public function index()
    {
        // Jika user adalah operator, hanya tampilkan total obat
        if (Auth::user()->level == 'operator') {
            $totalObat = Obat::count();
            return view('dashboard.operator', compact('totalObat'));
        }

        return redirect()->route('dashboard.index')->with('error', 'Unauthorized access');
    }
}
