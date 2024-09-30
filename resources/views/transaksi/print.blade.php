@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="mb-4 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 100px;">
            <h4>Laporan Transaksi Obat</h4>
            <p>{{ date('d F Y') }}</p>
        </div>

        <table class="table-bordered mt-4 table">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Obat</th>
                    <th>Dosis</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Harga (Rp)</th>
                    <th>Nama Pemesan</th>
                    <th>Ruangan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $transaksi)
                    <tr>
                        <td>{{ $transaksi->id }}</td>
                        <td>{{ $transaksi->nama_obat }}</td>
                        <td>{{ $transaksi->dosis }}</td>
                        <td>{{ $transaksi->jenis }}</td>
                        <td>{{ $transaksi->jumlah }}</td>
                        <td>{{ number_format($transaksi->harga, 2, ',', '.') }}</td>
                        <td>{{ $transaksi->nama_pemesan }}</td>
                        <td>{{ $transaksi->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 text-right">
            <button onclick="window.print();" class="btn btn-primary">Print</button>
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }

            .container {
                width: 100%;
                padding: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 10px;
                border: 1px solid #000;
            }

            th {
                background-color: #f2f2f2;
            }
        }
    </style>
@endsection
