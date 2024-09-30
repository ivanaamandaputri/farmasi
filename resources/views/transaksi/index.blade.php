@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="container-fluid d-flex justify-content-between">
            <h4 class="card-title">Data Transaksi</h4>
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary mb-3">Tambah Transaksi</a>
        </div>
        <div class="table-responsive">
            <table class="table-striped table-hover table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Obat</th>
                        <th>Dosis</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Nama Pemesan</th>
                        <th>Ruangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nama_obat }}</td>
                            <td>{{ $item->dosis }}</td>
                            <td>{{ $item->jenis }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ number_format($item->harga, 2, ',', '.') }}</td> <!-- Format harga -->
                            <td>{{ $item->nama_pemesan }}</td>
                            <td>{{ $item->ruangan }}</td>
                            <td>
                                <a href="{{ route('transaksi.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Apakah yakin ingin menghapus?')">Hapus</button>
                                </form>
                                <a href="{{ route('transaksi.print', $item->id) }}" class="btn btn-info">Cetak</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
