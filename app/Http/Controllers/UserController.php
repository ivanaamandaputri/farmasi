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
        $user = User::all();
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
        $request->validate([
            'nip' => 'required|string|unique:user',
            'password' => 'required|string|min:6',
            'level' => 'required|string|in:admin,operator',
            'nama_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string',
            'ruangan' => 'required|string',
        ]);

        User::create([
            'nip' => $request->nip,
            'password' => bcrypt($request->password),
            'level' => $request->level,
            'nama_pegawai' => $request->nama_pegawai,
            'jabatan' => $request->jabatan,
            'ruangan' => $request->ruangan,
        ]);

        return redirect()->route('user.index')->with('success', 'User created successfully.');
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
        $request->validate([
            'nip' => 'required|string|unique:user,nip,' . $id,
            'password' => 'nullable|string|min:6',
            'level' => 'required|string|in:admin,operator',
            'nama_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string',
            'ruangan' => 'required|string',
        ]);

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

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }
}
