@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <h4 class="card-title">Data Permintaan Obat</h4>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table-striped table-hover table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Obat</th>
                                <th>Dosis</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Harga (Rp)</th>
                                <th>Total (Rp)</th>
                                <th>Nama Pemesan</th>
                                <th>Ruangan</th>
                                <th>Waktu Order</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
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
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->status === 'Disetujui' || $item->status === 'Ditolak')
                                            @if ($item->status === 'Ditolak')
                                                <button type="button" class="btn btn-sm btn-info view-reason-btn"
                                                    data-reason="{{ $item->alasan_penolakan }}">Alasan</button>
                                            @endif
                                        @else
                                            <button type="button" class="btn btn-sm btn-success approve-btn"
                                                data-id="{{ $item->id }}">Setuju</button>
                                            <button type="button" class="btn btn-sm btn-danger reject-btn"
                                                data-id="{{ $item->id }}">Tolak</button>
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

    <!-- Modal Konfirmasi Setuju -->
    <div class="modal fade" id="confirmApproveModal" tabindex="-1" aria-labelledby="confirmApproveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Persetujuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Apakah Anda yakin ingin menyetujui transaksi ini?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning" id="confirmApproveButton">Setuju</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Tolak -->
    <div class="modal fade" id="confirmRejectModal" tabindex="-1" aria-labelledby="confirmRejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menolak transaksi ini?
                    <textarea class="form-control" id="rejectReason" rows="3" placeholder="Masukkan alasan"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmRejectButton">Tolak</button>
                </div>
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

    <!-- Toast / Alert Konfirmasi -->
    <div id="confirmationToast" class="toast" role="alert" style="position: fixed; top: 20px; right: 20px;">
        <div class="toast-body">
            <span id="toastMessage"></span>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        let transactionId;

        function showToast(message) {
            $('#toastMessage').text(message);
            const toast = new bootstrap.Toast($('#confirmationToast'));
            toast.show();
        }

        $(document).on('click', '.approve-btn', function() {
            transactionId = $(this).data('id');
            $('#confirmApproveModal').modal('show');
        });

        $('#confirmApproveButton').on('click', function() {
            $.ajax({
                url: `/transaksi/approve/${transactionId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    showToast(response.message); // Menampilkan pesan sukses
                    setTimeout(() => location.reload()); // Reload halaman setelah menampilkan toast
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON.error); // Menampilkan pesan error jika ada
                }
            });
            $('#confirmApproveModal').modal('hide');
        });

        $(document).on('click', '.reject-btn', function() {
            transactionId = $(this).data('id');
            $('#confirmRejectModal').modal('show');
        });

        $('#confirmRejectButton').on('click', function() {
            const reason = $('#rejectReason').val();
            if (!reason) {
                alert('Alasan penolakan harus diisi');
                return;
            }

            $.ajax({
                url: `/transaksi/reject/${transactionId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    alasan: reason
                },
                success: function(response) {
                    showToast(response.message); // Menampilkan pesan sukses
                    setTimeout(() => location.reload()); // Reload halaman setelah menampilkan toast
                },
                error: function(xhr) {
                    showToast(xhr.responseJSON.error); // Menampilkan pesan error jika ada
                }
            });
            $('#confirmRejectModal').modal('hide');
        });

        $(document).on('click', '.view-reason-btn', function() {
            const reason = $(this).data('reason');
            $('#reasonText').text(reason);
            $('#reasonModal').modal('show');
        });
    </script>
@endsection
