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
                    @foreach ($obat as $obat)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $obat->nama_obat }}</td>
                            <td>{{ $obat->dosis }}</td>
                            <td>{{ $obat->jenis }}</td>
                            <td>{{ $obat->jumlah }}</td>
                            <td>{{ $obat->harga }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
