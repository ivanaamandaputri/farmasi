@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <!-- Header -->
        <div class="container-fluid d-flex justify-content-between">
            <h4 class="card-title">Daftar Jenis Obat</h4>
            <a href="{{ route('jenis_obat.create') }}" class="btn btn-primary mb-3">Tambah Jenis</a>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card untuk tabel -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table-hover table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama Jenis Obat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jenisObat as $jenis)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $jenis->nama_jenis }}</td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('jenis_obat.edit', $jenis->id) }}"
                                            class="btn btn-warning">Edit</a>

                                        <!-- Form Hapus -->
                                        <form action="{{ route('jenis_obat.destroy', $jenis->id) }}" method="POST"
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

    <!-- Modal Peringatan -->
    <div class="modal fade" id="modalPeringatan" tabindex="-1" role="dialog" aria-labelledby="modalPeringatanLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPeringatanLabel">Peringatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ session('error') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>



    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Kesan timbul 3D */
        }

        $(document).ready(function () {

                // Jika session error ada, tampilkan modal
                @if (session('error'))
                    $('#modalPeringatan').modal('show');
                @endif
            });
    </style>
@endsection
