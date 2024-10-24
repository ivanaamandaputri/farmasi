@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Detail Obat: {{ $obat->nama_obat }}</h4>
        <br>
        <div class="card mb-4" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Dosis:</strong> {{ $obat->dosis }}</li>
                    <li class="list-group-item"><strong>Jenis:</strong> {{ $obat->jenis }}</li>
                    <li class="list-group-item">
                        <strong>Stok:</strong> {{ $obat->stok }}
                        @if ($obat->stok == 0)
                            <span class="badge badge-danger">Stok Habis!</span>
                        @elseif ($obat->stok < 5)
                            <span class="badge badge-warning">Hampir Habis!</span>
                        @endif
                    </li>

                    <li class="list-group-item"><strong>Harga:</strong> Rp {{ number_format($obat->harga, 0, ',', '.') }}
                    </li>
                </ul>
            </div>
        </div>

        <a href="{{ route('obat.index') }}" class="btn btn-primary mt-3">Kembali</a>
    </div>
@endsection
