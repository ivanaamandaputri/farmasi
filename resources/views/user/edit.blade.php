@extends('layouts.app')
@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Edit User</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" name="nip" class="form-control" value="{{ $user->nip }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_pegawai">Nama Pegawai</label>
                        <input type="text" name="nama_pegawai" class="form-control" value="{{ $user->nama_pegawai }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <select name="jabatan" class="form-control" required>
                            <option value="Kepala Apotik" {{ $user->jabatan == 'Kepala Apotik' ? 'selected' : '' }}>Kepala
                                Apotik</option>
                            <option value="Apoteker" {{ $user->jabatan == 'Apoteker' ? 'selected' : '' }}>Apoteker</option>
                            <option value="Staf" {{ $user->jabatan == 'Staf' ? 'selected' : '' }}>Staf</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ruangan">Ruangan</label>
                        <select name="ruangan" class="form-control" required>
                            <option value="Instalasi Farmasi" {{ $user->ruangan == 'Instalasi Farmasi' ? 'selected' : '' }}>
                                Instalasi Farmasi</option>
                            <option value="puskesmas Kaligangsa"
                                {{ $user->ruangan == 'puskesmas Kaligangsa' ? 'selected' : '' }}>Puskesmas Kaligangsa
                            </option>
                            <option value="puskesmas Margadana"
                                {{ $user->ruangan == 'puskesmas Margadana' ? 'selected' : '' }}>Puskesmas Margadana</option>
                            <option value="puskesmas Tegal Barat"
                                {{ $user->ruangan == 'puskesmas Tegal Barat' ? 'selected' : '' }}>Puskesmas Tegal Barat
                            </option>
                            <option value="puskesmas Debong Lor"
                                {{ $user->ruangan == 'puskesmas Debong Lor' ? 'selected' : '' }}>Puskesmas Debong Lor
                            </option>
                            <option value="puskesmas Tegal Timur"
                                {{ $user->ruangan == 'puskesmas Tegal Timur' ? 'selected' : '' }}>Puskesmas Tegal Timur
                            </option>
                            <option value="puskesmas Slerok" {{ $user->ruangan == 'puskesmas Slerok' ? 'selected' : '' }}>
                                Puskesmas Slerok</option>
                            <option value="puskesmas Tegal Selatan"
                                {{ $user->ruangan == 'puskesmas Tegal Selatan' ? 'selected' : '' }}>Puskesmas Tegal Selatan
                            </option>
                            <option value="puskesmas Bandung"
                                {{ $user->ruangan == 'puskesmas Bandung' ? 'selected' : '' }}>Puskesmas Bandung</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level">Level</label>
                        <select name="level" class="form-control" required>
                            <option value="admin" {{ $user->level == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="operator" {{ $user->level == 'operator' ? 'selected' : '' }}>Operator</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password (Opsional)</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Kosongkan jika tidak ingin mengganti password">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
