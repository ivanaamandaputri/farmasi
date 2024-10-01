@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Edit Obat</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('obat.update', $obat->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_obat">Nama Obat</label>
                        <input type="text" name="nama_obat" class="form-control" value="{{ $obat->nama_obat }}" required>
                    </div>
                    <div class="form-group">
                        <label for="dosis">Dosis</label>
                        <input type="text" name="dosis" class="form-control" value="{{ $obat->dosis }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <select name="jenis" class="form-control" required>
                            <option value="tablet" {{ $obat->jenis == 'tablet' ? 'selected' : '' }}>Tablet</option>
                            <option value="kapsul" {{ $obat->jenis == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                            <option value="botol" {{ $obat->jenis == 'botol' ? 'selected' : '' }}>Botol</option>
                            <option value="dus" {{ $obat->jenis == 'dus' ? 'selected' : '' }}>Dus</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" value="{{ $obat->jumlah }}" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" name="harga" class="form-control" value="{{ $obat->harga }}" required>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
