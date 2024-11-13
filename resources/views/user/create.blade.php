@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Tambah User</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="foto">Foto</label><br>
                                <img id="preview-image" src="{{ asset('images/default-avatar.png') }}" alt="Preview Foto"
                                    style="max-width: 100%; height: auto; object-fit: cover;">
                                <input type="file" name="foto" id="foto" class="form-control mt-2"
                                    accept="image/*" onchange="previewImage(event)">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" name="nip" class="form-control" placeholder="Masukkan NIP"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="nama_pegawai">Nama Pegawai</label>
                                <input type="text" name="nama_pegawai" class="form-control"
                                    placeholder="Masukkan nama lengkap pegawai" required>
                            </div>

                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <select name="jabatan" class="form-control" required>
                                    <option value="" disabled selected>Pilih jabatan</option>
                                    <option value="Kepala Apotik">Kepala Apotik</option>
                                    <option value="Apoteker">Apoteker</option>
                                    <option value="Staf">Staf</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="ruangan">Ruangan</label>
                                <select name="ruangan" class="form-control" required>
                                    <option value="" disabled selected>Pilih ruangan</option>
                                    <option value="Instalasi Farmasi">Instalasi Farmasi</option>
                                    <option value="Puskesmas Kaligangsa">Puskesmas Kaligangsa</option>
                                    <!-- Tambahkan pilihan lain sesuai kebutuhan -->
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="level">Level</label>
                                <select name="level" class="form-control" required>
                                    <option value="" disabled selected>Pilih hak akses</option>
                                    <option value="admin">Admin</option>
                                    <option value="operator">Operator</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Masukkan 6 karakter yang terdiri dari huruf dan angka" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview-image');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
