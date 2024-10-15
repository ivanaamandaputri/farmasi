@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="mb-4 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100px;">
            <h4>Laporan Pemesanan Obat Bulanan</h4>
            <p>{{ date('d F Y') }}</p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Dosis</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Harga (Rp)</th>
                    <th>Total</th>
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
                        <td>{{ $transaksi->obat->dosis }}</td>
                        <td>{{ $transaksi->obat->jenis }}</td>
                        <td>{{ $transaksi->jumlah }}</td>
                        <td>{{ number_format($transaksi->obat->harga) }}</td> <!-- Format harga -->
                        <td>{{ number_format($transaksi->total) }}</td> <!-- Format total -->
                        <td>{{ $transaksi->user->nama_pegawai }}</td>
                        <td>{{ $transaksi->user->ruangan }}</td>
                        <td>{{ $transaksi->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 text-right">
            <button onclick="window.print();" class="btn btn-primary">Print</button>
        </div>
    </div>
@endsection
