@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <!-- Judul dan tombol berada di satu baris -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Data Order Obat</h4>
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary">Tambah Transaksi</a>
        </div>

        <!-- Card -->
        <div class="card mb-4" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); border: none;">
            <div class="card-body">
                <div class="table-responsive">
                    <table id=""" class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="bg-light">Tanggal</th> <!-- Menambahkan kelas bg-light untuk warna abu muda -->
                                <th>Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedTransaksi as $tanggal => $transaksiPerTanggal)
                                <tr>
                                    <td>
                                        <button class="btn btn-sm btn-info toggle-collapse" data-bs-toggle="collapse"
                                            data-bs-target="#collapse-{{ $tanggal }}">
                                            +
                                        </button>
                                    </td>
                                    <td class="bg-light">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</td>
                                    <td>Lihat Transaksi</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="p-0">
                                        <div id="collapse-{{ $tanggal }}" class="collapse">
                                            <table class="mb-0 table table shadow-sm">
                                                <!-- Tambahkan shadow-sm untuk efek timbul -->
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Obat</th>
                                                        <th>Dosis</th>
                                                        <th>Jenis</th>
                                                        <th>Jumlah</th>
                                                        <th>Acc</th>
                                                        <th>Harga (Rp)</th>
                                                        <th>Total (Rp)</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $number = 1; @endphp
                                                    @foreach ($transaksiPerTanggal as $item)
                                                        <tr>
                                                            <td>{{ $number++ }}</td>
                                                            <td>{{ $item->obat->nama_obat }}</td>
                                                            <td>{{ $item->obat->dosis }}</td>
                                                            <td>{{ $item->obat->jenisObat->nama_jenis }}</td>
                                                            <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                                            <td>{{ optional($item->transaksi)->acc ?? '' }}</td>
                                                            <td>{{ number_format($item->obat->harga, 0, ',', '.') }}</td>
                                                            <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                                                            <td>
                                                                @if ($item->status === 'Disetujui')
                                                                    <span class="badge bg-success">Disetujui</span>
                                                                @elseif ($item->status === 'Ditolak')
                                                                    <span class="badge bg-danger">Ditolak</span>
                                                                @else
                                                                    <span class="badge bg-warning">Menunggu</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($item->status === 'Ditolak')
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-light view-reason-btn"
                                                                        data-reason="{{ $item->alasan_penolakan }}">Alasan</button>
                                                                @elseif ($item->status === 'Menunggu')
                                                                    <a href="{{ route('transaksi.edit', $item->id) }}"
                                                                        class="btn btn-warning btn-sm">Edit</a>
                                                                    <form
                                                                        action="{{ route('transaksi.destroy', $item->id) }}"
                                                                        method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-danger btn-sm">Hapus</button>
                                                                    </form>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Alasan Penolakan -->
        <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Alasan Penolakan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="reasonText"></p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.querySelectorAll('.view-reason-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const reason = this.getAttribute('data-reason');
                    document.getElementById('reasonText').textContent = reason || 'Alasan tidak tersedia';
                    $('#reasonModal').modal('show');
                });
            });

            // Lihat alasan penolakan
            $(document).on('click', '.view-reason-btn', function() {
                const reason = $(this).data('reason');
                $('#reasonText').text(reason);
                $('#reasonModal').modal('show');
            });
        </script>
    @endsection
