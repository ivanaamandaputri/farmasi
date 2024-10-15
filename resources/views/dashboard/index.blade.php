@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="container-fluid d-flex justify-content-between">
            <h4 class="card-title">Data Obat</h4>
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($obat as $item)
                        <!-- Ubah variabel untuk menghindari kebingungan -->
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_obat }}</td>
                            <td>{{ $item->dosis }}</td>
                            <td>{{ $item->jenis }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->harga }}</td>
                            <td>
                                <a href="{{ route('obat.show', $item->id) }}" class="btn btn-info">Lihat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-xl-3 col-md-6 mt-4">
            <div class="card bg-primary mb-4 text-white">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-pills fa-2x me-3"></i>
                        <span>Jumlah Obat</span>
                    </div>
                    <div class="h5 font-weight-bold mb-0">{{ $totalObat }}</div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small stretched-link text-white" href="{{ route('dashboard.index') }}">Lihat Detail</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>
@endsection
