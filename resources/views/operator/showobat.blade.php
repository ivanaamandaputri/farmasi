@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <h4 class="mb-4">Detail Obat</h4>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <div class="card-body text-center">
                        @if ($obat->foto)
                            <img src="{{ asset('storage/' . $obat->foto) }}" alt="Foto {{ $obat->nama_obat }}"
                                class="img-fluid" style="max-width: 100%; max-height: 300px; object-fit: cover;">
                        @else
                            <img src="{{ asset('path/to/default-image.jpg') }}" alt="Foto Tidak Tersedia" class="img-fluid"
                                style="max-width: 100%; max-height: 300px; object-fit: cover;">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <div class="card-body">
                        <h5 class="badge bg-secondary p-2" style="font-size: 1.0rem;">{{ $obat->nama_obat }}</h5>
                        <ul class="list-group list-group-flush mt-3">
                            <li class="list-group-item"><strong>Dosis:</strong> {{ $obat->dosis }}</li>
                            <li class="list-group-item"><strong>Jenis:</strong> {{ $obat->jenis }}</li>
                            <li class="list-group-item">
                                <strong>Stok:</strong> {{ number_format($obat->stok, 0, ',', '.') }}
                                @if ($obat->stok == 0)
                                    <span class="badge badge-danger">Stok Habis!</span>
                                @elseif ($obat->stok < 5)
                                    <span class="badge badge-warning">Hampir Habis!</span>
                                @endif
                            </li>
                            <li class="list-group-item"><strong>Harga:</strong> Rp
                                {{ number_format($obat->harga, 0, ',', '.') }}</li>
                            <li class="list-group-item"><strong>Tanggal Kadaluwarsa:</strong>
                                {{ \Carbon\Carbon::parse($obat->exp)->locale('id')->translatedFormat('j M Y') }}</li>
                            <li class="list-group-item">
                                <strong>Keterangan: <br></strong> {!! strip_tags($obat->keterangan, '<p><strong><ul><li>') !!}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('operator.dataobat') }}" class="btn btn-primary mt-3">Kembali</a>

    </div>
    <script>
        // Initialize CKEditor
        CKEDITOR.replace('keterangan');
    </script>
@endsection
