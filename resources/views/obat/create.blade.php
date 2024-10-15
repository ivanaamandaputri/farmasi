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

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </div>
                @endif

                <form action="{{ route('obat.store') }}" method="POST">
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
                        <select name="jenis" class="form-control" required>
                            <option value="" disabled selected>Pilih jenis obat</option>
                            <option value="tablet">Tablet</option>
                            <option value="botol">Botol</option>
                            <option value="dus">Dus</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" name="harga" class="form-control"
                            placeholder="Masukkan harga obat (contoh: 10000)" required>
                    </div>
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" name="stok" class="form-control"
                            placeholder="Masukkan jumlah stok obat ini" required>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('obat.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
