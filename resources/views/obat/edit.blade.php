@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card">
            <div class="card-header">
                <h4>Edit Obat</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </div>
                @endif

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
                            <option value="botol" {{ $obat->jenis == 'botol' ? 'selected' : '' }}>Botol</option>
                            <option value="dus" {{ $obat->jenis == 'dus' ? 'selected' : '' }}>Dus</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" name="harga" class="form-control" value="{{ $obat->harga }}" required>
                    </div>
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" name="stok" class="form-control" value="{{ $obat->stok }}" required>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('obat.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
