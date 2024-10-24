@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="container-fluid d-flex justify-content-between">
            <h4 class="card-title">Data Order Obat</h4> <br>
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary mb-3">Tambah Transaksi</a>
        </div>
        <div class="card mb-4">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="d-flex justify-content-between">
                <div class="container-fluid d-flex justify-content-between">
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
                                    <td>
                                        @if ($item->status === 'Disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif ($item->status === 'Ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>
                                        @if ($item->status === 'Pengajuan')
                                            <a href="{{ route('transaksi.edit', $item->id) }}"
                                                class="btn btn-warning">Edit</a>
                                            <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Apakah yakin ingin menghapus?')">Hapus</button>
                                            </form>
                                        @elseif ($item->status === 'Ditolak')
                                            <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                                data-bs-target="#modalAlasan{{ $item->id }}">
                                                Alasan
                                            </button>

                                            <!-- Modal Alasan -->
                                            <div class="modal fade" id="modalAlasan{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Alasan Penolakan
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ $item->alasan_penolakan }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
