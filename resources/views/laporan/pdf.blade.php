<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <h1>Laporan Pemesanan Obat Bulanan</h1>
    <div class="table-responsive">
        <table>
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
</body>

</html>
