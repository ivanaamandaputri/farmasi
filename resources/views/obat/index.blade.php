@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="container-fluid d-flex justify-content-between">
            <h4 class="card-title">Data Obat</h4>
            <a href="{{ route('obat.create') }}" class="btn btn-primary mb-3">Tambah Obat</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table-striped table-hover table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Obat</th>
                                <th>Dosis</th>
                                <th>Jenis</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($obat as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_obat }}</td>
                                    <td>{{ $item->dosis }}</td>
                                    <td>{{ $item->jenis }}</td>
                                    <td>{{ $item->harga }}</td>
                                    <td>
                                        {{ $item->stok }}
                                        <!-- Tampilkan peringatan jika stok hampir habis -->
                                        @if ($item->stok < 5)
                                            <!-- Contoh threshold untuk peringatan -->
                                            <span class="badge badge-danger">Hampir Habis, Restok Segera!</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('obat.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('obat.destroy', $item->id) }}" method="POST"
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
