@extends('layouts.app')
@section('content')
    <h4>Laporan Pemesanan Obat Bulanan</h4>

    <table border="1" cellspacing="0" cellpadding="5">
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
                    <td>{{ $transaksi->harga }}</td>
                    <td>{{ $transaksi->user->nama_pegawai }}</td>
                    <td>{{ $transaksi->ruangan }}</td>
                    <td>{{ $transaksi->created_at->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
