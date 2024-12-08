@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Edit User</h4>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">
                <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Left Column for Photo -->
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <label for="foto"></label>
                                <!-- Display current photo if exists -->
                                <div>
                                    @if ($user->foto)
                                        <img src="{{ asset('storage/user/' . $user->foto) }}" alt="Foto User"
                                            class="img-fluid custom-photo mt-2" width="200">
                                    @else
                                        <img src="https://via.placeholder.com/150" alt="Foto tidak tersedia"
                                            class="img-fluid custom-photo mt-2" width="200">
                                    @endif
                                </div>
                                <input type="file" name="foto" class="form-control mt-2"
                                    onchange="previewImage(event)">
                            </div>
                        </div>

                        <!-- Right Column for Other Inputs -->
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" name="nip" class="form-control" value="{{ $user->nip }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="nama_pegawai">Nama Pegawai</label>
                                <input type="text" name="nama_pegawai" class="form-control"
                                    value="{{ $user->nama_pegawai }}" required>
                            </div>
                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <select name="jabatan" class="form-control" required>
                                    <option value="Kepala Apotik" {{ $user->jabatan == 'Kepala Apotik' ? 'selected' : '' }}>
                                        Kepala Apotik</option>
                                    <option value="Apoteker" {{ $user->jabatan == 'Apoteker' ? 'selected' : '' }}>Apoteker
                                    </option>
                                    <option value="Staf" {{ $user->jabatan == 'Staf' ? 'selected' : '' }}>Staf</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ruangan">Ruangan</label>
                                <select name="ruangan" class="form-control" required>
                                    <option value="Instalasi Farmasi"
                                        {{ $user->ruangan == 'Instalasi Farmasi' ? 'selected' : '' }}>Instalasi Farmasi
                                    </option>
                                    <option value="puskesmas Kaligangsa"
                                        {{ $user->ruangan == 'puskesmas Kaligangsa' ? 'selected' : '' }}>Puskesmas
                                        Kaligangsa</option>
                                    <option value="puskesmas Margadana"
                                        {{ $user->ruangan == 'puskesmas Margadana' ? 'selected' : '' }}>Puskesmas Margadana
                                    </option>
                                    <option value="puskesmas Tegal Barat"
                                        {{ $user->ruangan == 'puskesmas Tegal Barat' ? 'selected' : '' }}>Puskesmas Tegal
                                        Barat</option>
                                    <option value="puskesmas Debong Lor"
                                        {{ $user->ruangan == 'puskesmas Debong Lor' ? 'selected' : '' }}>Puskesmas Debong
                                        Lor</option>
                                    <option value="puskesmas Tegal Timur"
                                        {{ $user->ruangan == 'puskesmas Tegal Timur' ? 'selected' : '' }}>Puskesmas Tegal
                                        Timur</option>
                                    <option value="puskesmas Slerok"
                                        {{ $user->ruangan == 'puskesmas Slerok' ? 'selected' : '' }}>Puskesmas Slerok
                                    </option>
                                    <option value="puskesmas Tegal Selatan"
                                        {{ $user->ruangan == 'puskesmas Tegal Selatan' ? 'selected' : '' }}>Puskesmas Tegal
                                        Selatan</option>
                                    <option value="puskesmas Bandung"
                                        {{ $user->ruangan == 'puskesmas Bandung' ? 'selected' : '' }}>Puskesmas Bandung
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="level">Level</label>
                                <select name="level" class="form-control" required>
                                    <option value="admin" {{ $user->level == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="operator" {{ $user->level == 'operator' ? 'selected' : '' }}>Operator
                                    </option>
                                </select>
                            </div>
                            <!-- Input untuk Password Baru -->
                            <div class="form-group">
                                <label for="password">Password Baru</label>
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Minimal 6 karakter, huruf dan angka">
                                @error('password')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Input untuk Konfirmasi Password Baru -->
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Konfirmasi password baru">
                                @error('password_confirmation')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>


                            <br>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Mengatur ukuran lingkaran dan memastikan gambar tidak peyang */
        .custom-photo {
            width: 200px;
            /* Ukuran lingkaran lebih besar */
            height: 200px;
            /* Ukuran lingkaran lebih besar */
            object-fit: cover;
            /* Gambar akan menyesuaikan dengan frame */
            border-radius: 15px;
            /* Membuat gambar menjadi persegi tumpul */
        }
    </style>

    <script>
        function previewImage(event) {
            const output = document.querySelector('.form-group img');
            output.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection
