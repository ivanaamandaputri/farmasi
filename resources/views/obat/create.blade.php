@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Obat</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('obat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nama_obat">Nama Obat</label>
                        <input type="text" name="nama_obat" class="form-control" placeholder="Masukkan nama obat"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="dosis">Dosis</label>
                        <input type="text" name="dosis" class="form-control"
                            placeholder="Masukkan dosis obat (contoh: 100 Mg)" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <select name="jenis_obat_id" class="form-control" required>
                            <option value="" disabled selected>Pilih jenis obat</option>
                            @foreach ($jenisObat as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control"
                            placeholder="Masukkan harga obat (contoh: 10000)" required>
                    </div>
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" name="stok" class="form-control"
                            placeholder="Masukkan jumlah stok obat ini (contoh: 1000)" required>
                    </div>
                    <div class="form-group">
                        <label for="exp">Tanggal Kadaluwarsa</label>
                        <input type="date" name="exp" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan Obat</label>
                        <textarea name="keterangan" class="form-control" id="keterangan" rows="3" placeholder="Masukkan keterangan obat"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Obat</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('obat.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize CKEditor
        CKEDITOR.replace('keterangan');
    </script>
@endsection
