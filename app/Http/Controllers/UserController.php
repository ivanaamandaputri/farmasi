<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Foto (optional)
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

        // Menangani upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $fotoPath = $request->foto->store('images/users', 'public'); // Menyimpan foto di folder public/images/users
        }

        // Simpan data user
        User::create([
            'nip' => $request->nip,
            'password' => bcrypt($request->password), // Encrypt password
            'level' => $request->level,
            'nama_pegawai' => $request->nama_pegawai,
            'jabatan' => $request->jabatan,
            'ruangan' => $request->ruangan,
            'foto' => $fotoPath, // Menyimpan path foto
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'nip' => 'required',
            'nama_pegawai' => 'required',
            'jabatan' => 'required',
            'ruangan' => 'required',
            'level' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
        ]);

        $user = User::findOrFail($id);

        // Proses upload gambar jika ada
        if ($request->hasFile('foto')) {
            // Hapus gambar lama jika ada
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }

            // Upload gambar baru
            $filePath = $request->file('foto')->store('users', 'public'); // Menyimpan di folder 'storage/app/public/users'

            // Simpan nama file ke dalam kolom foto
            $user->foto = basename($filePath); // Simpan hanya nama file saja
        }

        // Update data pengguna lainnya
        $user->nip = $request->nip;
        $user->nama_pegawai = $request->nama_pegawai;
        $user->jabatan = $request->jabatan;
        $user->ruangan = $request->ruangan;
        $user->level = $request->level;

        // Jika password diubah
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        // Simpan perubahan
        $user->save();

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil diperbarui');
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
