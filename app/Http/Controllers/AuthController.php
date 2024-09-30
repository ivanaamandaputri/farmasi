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
            'nip' => ['required', 'string'], // Ubah 'username' menjadi 'nip'
            'password' => ['required', 'string'],
        ]);

        // Cek kredensial menggunakan Auth
        if (Auth::attempt($credentials)) {
            // Jika login berhasil, redirect ke halaman dashboard
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
        }

        // Jika login gagal, kembalikan ke halaman login dengan error
        return back()->withErrors([
            'nip' => 'NIP atau password salah.', // Ubah 'username' menjadi 'NIP'
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
