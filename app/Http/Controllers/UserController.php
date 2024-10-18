<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::orderBy('nama_pegawai', 'asc')
            ->orderBy('created_at', 'desc') // Mengurutkan data berdasarkan tanggal pembuatan, yang terbaru di atas
            ->get();
        return view('user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nip' => 'required|string|unique:user,nip',
            'password' => 'required|string|min:6',
            'level' => 'required|string|in:admin,operator',
            'nama_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string',
            'ruangan' => 'required|string',
        ], [
            'nip.required' => 'NIP harus diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'level.required' => 'Level harus dipilih',
            'nama_pegawai.required' => 'Nama pegawai harus diisi',
            'jabatan.required' => 'Jabatan harus dipilih',
            'ruangan.required' => 'Ruangan harus dipilih',
        ]);

        // Simpan data user
        User::create([
            'nip' => $request->nip,
            'password' => bcrypt($request->password), // Encrypt password
            'level' => $request->level,
            'nama_pegawai' => $request->nama_pegawai,
            'jabatan' => $request->jabatan,
            'ruangan' => $request->ruangan,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('user.index')->with('success', 'Data berhasil disimpan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'nip' => 'required|string|unique:user,nip,' . $id,
            'password' => 'nullable|string|min:6',
            'level' => 'required|string|in:admin,operator',
            'nama_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string',
            'ruangan' => 'required|string',
        ]);


        // Update data user
        $user = User::findOrFail($id);
        $user->nip = $request->nip;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->level = $request->level;
        $user->nama_pegawai = $request->nama_pegawai;
        $user->jabatan = $request->jabatan;
        $user->ruangan = $request->ruangan;
        $user->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Hapus user
        $user = User::findOrFail($id);
        $user->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }
}
