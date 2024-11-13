@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="container-fluid d-flex justify-content-between">
            <h4 class="card-title">Data User</h4>
            <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Tambah User</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table-striped table-hover table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Jabatan</th>
                                <th>Ruangan</th>
                                <th>Level</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nip }}</td>
                                    <td>{{ $item->nama_pegawai }}</td>
                                    <td>{{ $item->jabatan }}</td>
                                    <td>{{ $item->ruangan }}</td>
                                    <td>
                                        @if ($item->level == 'admin')
                                            <span class="badge bg-success">{{ $item->level }}</span>
                                        @elseif($item->level == 'operator')
                                            <span class="badge bg-secondary">{{ $item->level }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $item->level }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Tombol Detail dengan gaya yang sama -->
                                        <a href="{{ route('user.show', $item->id) }}" class="btn btn-info btn">Detail</a>
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('user.edit', $item->id) }}" class="btn btn-warning btn">Edit</a>
                                        <!-- Form Hapus -->
                                        <form action="{{ route('user.destroy', $item->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn"
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

    <style>
        /* Mengatur ukuran lingkaran dan memastikan gambar tidak peyang */
        .custom-photo {
            width: 200px;
            /* Ukuran lingkaran lebih besar */
            height: 200px;
            /* Ukuran lingkaran lebih besar */
            object-fit: cover;
            /* Gambar akan menyesuaikan dengan frame */
            border-radius: 50%;
            /* Membuat gambar menjadi lingkaran */
        }
    </style>

    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Kesan timbul 3D */
        }
    </style>
@endsection
