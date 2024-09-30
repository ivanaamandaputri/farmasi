{{-- resources/views/transaksi/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
    <div class="container">
        <h1>Edit Transaksi</h1>

        <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama_obat">Nama Obat</label>
                <input type="text" name="nama_obat" class="form-control" value="{{ $transaksi->nama_obat }}" required>
            </div>
            <div class="form-group">
                <label for="dosis">Dosis</label>
                <input type="text" name="dosis" class="form-control" value="{{ $transaksi->dosis }}" required>
            </div>
            <div class="form-group">
                <label for="jenis">Jenis</label>
                <select name="jenis" class="form-control" required>
                    <option value="tablet" {{ $transaksi->jenis == 'tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="kapsul" {{ $transaksi->jenis == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                    <option value="botol" {{ $transaksi->jenis == 'botol' ? 'selected' : '' }}>Botol</option>
                    <option value="dus" {{ $transaksi->jenis == 'dus' ? 'selected' : '' }}>Dus</option>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" value="{{ $transaksi->jumlah }}" required>
            </div>
            <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" step="0.01" name="harga" class="form-control" value="{{ $transaksi->harga }}"
                    required>
            </div>
            <div class="form-group">
                <label for="nama_pemesan">Nama Pemesan</label>
                <input type="text" name="nama_pemesan" class="form-control" value="{{ $transaksi->nama_pemesan }}"
                    required>
            </div>
            <div class="form-group">
                <label for="ruangan">Ruangan</label>
                <select name="ruangan" class="form-control" required>
                    <option value="Instalasi Farmasi" {{ $transaksi->ruangan == 'Instalasi Farmasi' ? 'selected' : '' }}>
                        Instalasi Farmasi</option>
                    <option value="puskesmas Kaligangsa"
                        {{ $transaksi->ruangan == 'puskesmas Kaligangsa' ? 'selected' : '' }}>puskesmas Kaligangsa</option>
                    <option value="puskesmas Margadana"
                        {{ $transaksi->ruangan == 'puskesmas Margadana' ? 'selected' : '' }}>puskesmas Margadana</option>
                    <option value="puskesmas Tegal Barat"
                        {{ $transaksi->ruangan == 'puskesmas Tegal Barat' ? 'selected' : '' }}>puskesmas Tegal Barat
                    </option>
                    <option value="puskesmas Debong Lor"
                        {{ $transaksi->ruangan == 'puskesmas Debong Lor' ? 'selected' : '' }}>puskesmas Debong Lor</option>
                    <option value="puskesmas Tegal Timur"
                        {{ $transaksi->ruangan == 'puskesmas Tegal Timur' ? 'selected' : '' }}>puskesmas Tegal Timur
                    </option>
                    <option value="puskesmas Slerok" {{ $transaksi->ruangan == 'puskesmas Slerok' ? 'selected' : '' }}>
                        puskesmas Slerok</option>
                    <option value="puskesmas Tegal Selatan"
                        {{ $transaksi->ruangan == 'puskesmas Tegal Selatan' ? 'selected' : '' }}>puskesmas Tegal Selatan
                    </option>
                    <option value="puskesmas Bandung" {{ $transaksi->ruangan == 'puskesmas Bandung' ? 'selected' : '' }}>
                        puskesmas Bandung</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
