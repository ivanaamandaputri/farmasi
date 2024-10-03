<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Coba untuk login
        if (Auth::attempt($credentials)) {
            // Redirect sesuai level user
            return redirect()->route(Auth::user()->level == 'admin' ? 'dashboard.index' : 'dashboard.operator')
                ->with('success', 'Login berhasil!'); // Tambahkan pesan sukses
        }

        // Jika login gagal
        return back()->withErrors([
            'nip' => 'NIP atau password salah.',
        ])->onlyInput('nip');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
