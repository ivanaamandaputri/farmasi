@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="container-fluid d-flex justify-content-between">
            <h4 class="card-title">Laporan Pemesanan Obat Bulanan</h4>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table-striped table-hover table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Dosis</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Nama Pemesan</th>
                        <th>Ruangan</th>
                        <th>Tanggal Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporanTransaksi as $transaksi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaksi->obat->nama_obat }}</td>
                            <td>{{ $transaksi->dosis }}</td>
                            <td>{{ $transaksi->jenis }}</td>
                            <td>{{ $transaksi->jumlah }}</td>
                            <td>{{ number_format($transaksi->harga, 2, ',', '.') }}</td> <!-- Format harga -->
                            <td>{{ $transaksi->user->nama_pegawai }}</td>
                            <td>{{ $transaksi->ruangan }}</td>
                            <td>{{ $transaksi->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
