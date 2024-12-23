<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Obat Keluar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
            border: 1px solid #000;
        }

        .kop-surat {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .kop-surat img {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }

        .kop-surat .title {
            font-size: 18px;
            font-weight: bold;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin: 10px 0;
        }

        .sub-title {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .rekap-table,
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .rekap-table th,
        .data-table th {
            background-color: #f4f4f4;
            border: 1px solid #000;
            text-align: left;
            padding: 8px;
        }

        .rekap-table td,
        .data-table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .footer {
            margin-top: 40px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        .footer .signature {
            margin-top: 15px;
            font-weight: normal;
            line-height: 1.4;
        }

        .footer .signature p {
            margin: 3px 0;
            font-size: 14px;
        }

        .footer .signature p:last-child {
            margin-bottom: 0;
        }

        .footer .signature p b {
            font-weight: bold;
        }

        @media print {
            body {
                font-size: 12px;
            }

            .container {
                max-width: 100%;
                padding: 0;
                border: none;
            }

            .kop-surat img {
                width: 60px;
            }

            .footer,
            .signature {
                text-align: left;
            }

            .footer {
                margin-top: 40px;
            }

            button,
            .no-print {
                display: none;
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }

            h1 {
                font-size: 20px;
            }

            .rekap-table th,
            .data-table th,
            .rekap-table td,
            .data-table td {
                padding: 6px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <img src="{{ asset('img/dinkes.png') }}" alt="Logo Dinkes">
            <div class="title">
                DINAS KESEHATAN KOTA TEGAL<br>
                INSTALASI FARMASI
            </div>
        </div>

        <br>
        <!-- Judul -->
        <h1>Laporan Transaksi Obat Keluar</h1>
        <p>Bulan: {{ $bulan }} | Tahun: {{ $tahun }} | Ruangan: {{ $ruangan }} | Obat:
            {{ $obat ?? 'Semua Obat' }}</p>

        <!-- Rekap Total Permintaan -->
        <table class="rekap-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Total Jumlah</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporanTransaksi->groupBy('obat_id') as $obatId => $transaksiGroup)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ optional($transaksiGroup->first()->obat)->nama_obat ?? 'Data Obat Tidak Ada' }}</td>
                        <td>{{ $transaksiGroup->sum('acc') }}</td>
                        <td>{{ number_format($transaksiGroup->sum('total'), 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Detail Transaksi -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Instansi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporanTransaksi as $index => $transaksi)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>{{ $transaksi->obat->nama_obat }} - {{ $transaksi->obat->dosis }}
                            ({{ $transaksi->obat->jenisObat->nama_jenis }})</td>
                        <td>
                            @if ($transaksi->status == 'Disetujui')
                                @if ($transaksi->jumlah == $transaksi->acc)
                                    {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                @else
                                    {{ number_format($transaksi->jumlah - $transaksi->acc, 0, ',', '.') }}
                                @endif
                            @else
                                {{ number_format($transaksi->acc, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>
                            @if ($transaksi->status == 'Disetujui')
                                <span class="badge badge-success">{{ $transaksi->status }}</span>
                            @elseif($transaksi->status == 'Ditolak')
                                <span class="badge badge-warning">{{ $transaksi->status }}</span>
                            @else
                                <span class="badge badge-light">{{ $transaksi->status }}</span>
                            @endif
                        </td>
                        <td>{{ $transaksi->user->ruangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Tegal, {{ \Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}</p>
            <div class="signature">
                <p><b>Admin {{ auth()->user()->ruangan }}</b></p>
                <br> <br> <br>
                <p>______________________</p>
                <p><b>{{ auth()->user()->nama_pegawai }}</b></p>
                <p><b>NIP {{ auth()->user()->nip }}</b></p>
            </div>
        </div>

        <script>
            window.onload = function() {
                window.print();
            };
        </script>
</body>

</html>
