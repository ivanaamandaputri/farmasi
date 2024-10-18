@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="container-fluid d-flex justify-content-between mb-3">
            <h4 class="card-title">Data Order Obat Masuk</h4>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Modal Konfirmasi Setuju -->
        <div class="modal fade" id="confirmApproveModal" tabindex="-1" role="dialog" aria-labelledby="confirmApproveModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmApproveModalLabel">Konfirmasi Persetujuan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menyetujui transaksi ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="confirmApproveButton">Setujui</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Tolak -->
        <div class="modal fade" id="confirmRejectModal" tabindex="-1" role="dialog"
            aria-labelledby="confirmRejectModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmRejectModalLabel">Konfirmasi Penolakan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menolak transaksi ini?
                        <br> Silakan masukkan alasan penolakan
                        <textarea class="form-control" id="rejectReason" rows="3" placeholder="Masukkan alasan"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmRejectButton">Tolak</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="transaksi-table" class="display table-striped table-hover table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Dosis</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Harga (Rp)</th>
                                <th>Total</th>
                                <th>Nama Pemesan</th>
                                <th>Ruangan</th>
                                <th>Waktu Transaksi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transaksi-body">
                            @if ($transaksi->isEmpty())
                                <tr>
                                    <td colspan="13">Tidak ada data transaksi.</td>
                                </tr>
                            @else
                                @foreach ($transaksi as $index => $item)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->obat->nama_obat }}</td>
                                        <td>{{ $item->obat->dosis }}</td>
                                        <td>{{ $item->obat->jenis }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>{{ number_format($item->obat->harga, 0, ',', '.') }}</td>
                                        <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                                        <td>{{ $item->user->nama_pegawai }}</td>
                                        <td>{{ $item->user->ruangan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</td>
                                        <td>
                                            @if ($item->status === 'Disetujui')
                                                <span class="badge badge-success">{{ $item->status }}</span>
                                            @elseif ($item->status === 'Ditolak')
                                                <span class="badge badge-danger">{{ $item->status }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ $item->status }}</span>
                                                <!-- Misalnya untuk status 'Menunggu' -->
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status === 'Disetujui' || $item->status === 'Ditolak')
                                                {{-- Jika status sudah disetujui atau ditolak, tidak ada tombol --}}
                                            @else
                                                <form action="{{ route('transaksi.approve', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="button" class="btn btn-primary approve-btn"
                                                        data-id="{{ $item->id }}">Setujui</button>
                                                </form>
                                                <form action="{{ route('transaksi.reject', $item->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="button" class="btn btn-danger reject-btn"
                                                        data-id="{{ $item->id }}">Tolak</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#transaksi-table').DataTable({
                "pageLength": 10,
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "zeroRecords": "Tidak ada data yang cocok",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada entri yang tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                    "search": "Cari:",
                    "paginate": {
                        "previous": "Sebelumnya",
                        "next": "Berikutnya"
                    }
                }
            });

            let transactionId; // Variabel untuk menyimpan ID transaksi

            // Klik tombol Setujui
            $(document).on('click', '.approve-btn', function() {
                transactionId = $(this).data('id'); // Ambil ID transaksi
                $('#confirmApproveModal').modal('show'); // Tampilkan modal konfirmasi setujui
            });

            // Klik tombol Tolak
            $(document).on('click', '.reject-btn', function() {
                transactionId = $(this).data('id'); // Ambil ID transaksi
                $('#confirmRejectModal').modal('show'); // Tampilkan modal konfirmasi tolak
            });

            // Konfirmasi Setujui
            $('#confirmApproveButton').on('click', function() {
                $.ajax({
                    url: `/transaksi/approve/${transactionId}`, // Arahkan ke route approve
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        location.reload(); // Refresh halaman setelah berhasil
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat menyetujui transaksi.');
                    }
                });
                $('#confirmApproveModal').modal('hide'); // Tutup modal setelah konfirmasi
            });

            // Konfirmasi Tolak
            $('#confirmRejectButton').on('click', function() {
                const reason = $('#rejectReason').val(); // Ambil alasan dari textarea
                if (!reason) {
                    alert('Alasan penolakan harus diisi.');
                    return;
                }

                $.ajax({
                    url: `/transaksi/reject/${transactionId}`, // Arahkan ke route reject
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        alasan: reason, // Kirim alasan ke backend
                    },
                    success: function(response) {
                        location.reload(); // Refresh halaman setelah berhasil
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat menolak transaksi.');
                    }
                });
                $('#confirmRejectModal').modal('hide'); // Tutup modal setelah konfirmasi
            });
        });
    </script>
@endsection
