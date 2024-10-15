@extends('layouts.app')
@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Tambah User</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" name="nip" class="form-control" placeholder="Masukkan NIP" required>
                    </div>
                    @error('nip')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div class="form-group">
                        <label for="nama_pegawai">Nama Pegawai</label>
                        <input type="text" name="nama_pegawai"
                            class="form-control"placeholder="Masukkan nama lengkap pegawai" required>
                        @error('nama_pegawai')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <select name="jabatan" class="form-control" required>
                            <option value="" disabled selected>Pilih jabatan</option>
                            <option value="Kepala Apotik">Kepala Apotik</option>
                            <option value="Apoteker">Apoteker</option>
                            <option value="Staf">Staf</option>
                        </select>
                        @error('jabatan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ruangan">Ruangan</label>
                        <select name="ruangan" class="form-control" required>
                            <option value="" disabled selected>Pilih ruangan</option>
                            <option value="Instalasi Farmasi">Instalasi Farmasi</option>
                            <option value="puskesmas Kaligangsa">Puskesmas Kaligangsa</option>
                            <option value="puskesmas Margadana">Puskesmas Margadana</option>
                            <option value="puskesmas Tegal Barat">Puskesmas Tegal Barat</option>
                            <option value="puskesmas Debong Lor">Puskesmas Debong Lor</option>
                            <option value="puskesmas Tegal Timur">Puskesmas Tegal Timur</option>
                            <option value="puskesmas Slerok">Puskesmas Slerok</option>
                            <option value="puskesmas Tegal Selatan">Puskesmas Tegal Selatan</option>
                            <option value="puskesmas Bandung">Puskesmas Bandung</option>
                        </select>
                        @error('ruangan')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="level">Level</label>
                        <select name="level" class="form-control" required>
                            <option value="" disabled selected>Pilih hak akses</option>
                            <option value="admin">Admin</option>
                            <option value="operator">Operator</option>
                        </select>
                        @error('level')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Masukkan 6 karakter yang terdiri dari huruf dan angka" required>
                    </div>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <br>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
