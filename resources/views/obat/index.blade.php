@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="container-fluid d-flex justify-content-between">
            <h4 class="card-title">Data Obat</h4>
            @if (!$readOnly)
                <!-- Jika bukan operator, tampilkan tombol tambah -->
                <a href="{{ route('obat.create') }}" class="btn btn-primary mb-3">Tambah Obat</a>
            @endif
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
                                        @if ($item->stok == 0)
                                            <span class="badge badge-danger">
                                                {{ auth()->user()->level == 'admin' ? 'Stok Habis, Restok Segera!' : 'Stok Habis!' }}
                                            </span>
                                        @elseif ($item->stok < 5)
                                            <span class="badge badge-warning">
                                                {{ auth()->user()->level == 'admin' ? 'Hampir Habis, Restok Segera!' : 'Hampir Habis!' }}
                                            </span>
                                        @endif
                                    </td>


                                    <td>
                                        @if ($readOnly)
                                            <!-- Jika operator, hanya tampilkan Lihat Detail -->
                                            <a href="{{ route('obat.show', $item->id) }}" class="btn btn-info">Lihat
                                                Detail</a>
                                        @else
                                            <a href="{{ route('obat.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                            <form action="{{ route('obat.destroy', $item->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Apakah yakin ingin menghapus?')">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Kesan timbul 3D */
        }
    </style>
@endsection
