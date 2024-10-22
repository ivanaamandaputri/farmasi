@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="card mb-4">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-header d-flex justify-content-between">
                <div class="container-fluid d-flex justify-content-between">
                    <h4 class="card-title">Data Order Obat</h4>
                    <a href="{{ route('transaksi.create') }}" class="btn btn-primary mb-3">Tambah Transaksi</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table-striped table-hover table">
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
                                <th>Status</th>
                                <th>Tanggal Transaksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->obat->nama_obat }}</td>
                                    <td>{{ $item->obat->dosis }}</td>
                                    <td>{{ $item->obat->jenis }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ $item->obat->harga }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>{{ $item->user->nama_pegawai }}</td>
                                    <td>{{ $item->user->ruangan }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('transaksi.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Apakah yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
