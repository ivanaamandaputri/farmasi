@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
    <div class="container">
        <h1>Tambah Transaksi</h1>

        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="obat_id">Nama Obat</label>
                <select name="obat_id" class="form-control" required>
                    @foreach ($obat as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_obat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="dosis">Dosis</label>
                <input type="text" name="dosis" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="jenis">Jenis</label>
                <select name="jenis" class="form-control" required>
                    <option value="tablet">Tablet</option>
                    <option value="kapsul">Kapsul</option>
                    <option value="botol">Botol</option>
                    <option value="dus">Dus</option>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" step="0.01" name="harga" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="user_id">Nama Pemesan</label>
                <select name="user_id" class="form-control" required>
                    @foreach ($user as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_pegawai }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="ruangan">Ruangan</label>
                <select name="ruangan" class="form-control" required>
                    <option value="Instalasi Farmasi">Instalasi Farmasi</option>
                    <option value="puskesmas Kaligangsa">puskesmas Kaligangsa</option>
                    <option value="puskesmas Margadana">puskesmas Margadana</option>
                    <option value="puskesmas Tegal Barat">puskesmas Tegal Barat</option>
                    <option value="puskesmas Debong Lor">puskesmas Debong Lor</option>
                    <option value="puskesmas Tegal Timur">puskesmas Tegal Timur</option>
                    <option value="puskesmas Slerok">puskesmas Slerok</option>
                    <option value="puskesmas Tegal Selatan">puskesmas Tegal Selatan</option>
                    <option value="puskesmas Bandung">puskesmas Bandung</option>
                </select>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
