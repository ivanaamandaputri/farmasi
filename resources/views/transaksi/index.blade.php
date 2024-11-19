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
                                        <button class="btn btn-sm btn-primary toggle-collapse" data-bs-toggle="collapse"
                                            data-bs-target="#collapse-{{ $tanggal }}">
                                            +
                                        </button>
                                    </td>
                                    <td class="bg-light">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</td>
                                    <td>Lihat Transaksi</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="p-0">
                                        <div class="collapse" id="collapse-{{ $tanggal }}">
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
                                                            <td>{{ optional($item->transaksi)->acc ?? '-' }}</td>
                                                            <td>{{ number_format($item->obat->harga, 0, ',', '.') }}</td>
                                                            <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                                                            <td>
                                                                @if ($item->status === 'Disetujui')
                                                                    <span class="badge bg-success">Disetujui</span>
                                                                @elseif ($item->status === 'Ditolak')
                                                                    <span class="badge bg-danger">Ditolak</span>
                                                                @elseif ($item->status === 'Selesai')
                                                                    <span class="badge bg-dark">Selesai</span>
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
                                                                @elseif ($item->status === 'Disetujui')
                                                                    <button type="button"
                                                                        class="btn btn-success btn-sm selesai-btn"
                                                                        data-id="{{ $item->id }}">Selesai</button>
                                                                    <button type="button"
                                                                        class="btn btn-warning btn-sm retur-btn"
                                                                        data-id="{{ $item->id }}"
                                                                        data-obat="{{ $item->obat->id }}"
                                                                        data-nama="{{ $item->obat->nama_obat }}">Retur</button>
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
        <div class="modal fade" id="reasonModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Alasan Penolakan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p id="reasonText"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Selesai -->
        <div class="modal fade" id="confirmSelesaiModal" tabindex="-1" aria-labelledby="confirmSelesaiModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmSelesaiModalLabel">Konfirmasi Selesai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menyelesaikan transaksi ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success" id="confirmSelesaiBtn">Ya, Selesai</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Retur -->
        <div class="modal fade" id="returModal" tabindex="-1" aria-labelledby="returModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="returModalLabel">Retur Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="returForm">
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="jumlah" required>
                            </div>
                            <div class="mb-3">
                                <label for="alasan" class="form-label">Alasan Retur</label>
                                <textarea class="form-control" id="alasan" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Retur</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Lihat alasan penolakan
            $(document).on('click', '.view-reason-btn', function() {
                const reason = $(this).data('reason');
                $('#reasonText').text(reason); // Mengisi alasan ke dalam modal
                $('#reasonModal').modal('show'); // Menampilkan modal
            });

            // untuk tombol selesai
            $(document).on('click', '.selesai-btn', function() {
                const transaksiId = $(this).data('id');
                $('#confirmSelesaiModal').modal('show');

                $('#confirmSelesaiBtn').on('click', function() {
                    $.ajax({
                        url: '/transaksi/selesai/' +
                            transaksiId, // Rute untuk menandai transaksi selesai
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            location.reload(); // Reload halaman setelah transaksi selesai
                        },
                        error: function(xhr, status, error) {
                            alert('Terjadi kesalahan saat menyelesaikan transaksi');
                        }
                    });
                    $('#confirmSelesaiModal').modal('hide');
                });
            });

            // untuk tombol retur 
            $(document).on('click', '.retur-btn', function() {
                const transaksiId = $(this).data('id');
                const obatId = $(this).data('obat');
                const namaObat = $(this).data('nama');

                $('#returModal').modal('show');

                $('#returForm').on('submit', function(e) {
                    e.preventDefault();

                    const jumlah = $('#jumlah').val();
                    const alasan = $('#alasan').val();
                    const password = $('#password').val();

                    $.ajax({
                        url: '/transaksi/retur', // Endpoint untuk mengirim retur
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            transaksi_id: transaksiId,
                            obat_id: obatId,
                            jumlah: jumlah,
                            alasan_retur: alasan,
                            password: password
                        },
                        success: function(response) {
                            alert('Retur berhasil diproses');
                            location.reload(); // Reload halaman setelah retur diproses
                        },
                        error: function(xhr, status, error) {
                            alert('Terjadi kesalahan saat memproses retur');
                        }
                    });

                    $('#returModal').modal('hide');
                });
            });
        </script>
    @endsection
