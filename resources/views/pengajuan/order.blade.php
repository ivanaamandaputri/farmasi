@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <div class="container-fluid d-flex justify-content-between card-header">
            <h4 class="card-title">Data Order Obat Masuk</h4>
        </div>

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
                        Apakah Anda yakin ingin menolak transaksi ini? Silakan masukkan alasan penolakan.
                        <textarea class="form-control" id="rejectReason" rows="3" placeholder="Masukkan alasan"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmRejectButton">Tolak</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table-striped table-hover table">
                    <tbody>
                        @if ($tanggalTransaksi->isEmpty())
                            <tr>
                                <td colspan="1">Tidak ada data transaksi.</td>
                            </tr>
                        @else
                            @foreach ($tanggalTransaksi as $tanggal)
                                <tr>
                                    <td>
                                        <a href="#" class="date-link" data-date="{{ $tanggal }}">
                                            {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <!-- Tabel untuk menampilkan transaksi -->
                <div id="transaksi-container" style="display:none;">
                    <h5>Transaksi pada <span id="selected-date"></span></h5>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        @endif

        $(document).ready(function() {
            let isTransaksiVisible = false; // Status visibilitas dropdown transaksi

            $('.date-link').on('click', function(e) {
                e.preventDefault();
                const date = $(this).data('date');
                $('#selected-date').text(date);

                // Jika dropdown sudah ditampilkan, tutup
                if (isTransaksiVisible && $('#transaksi-container').is(':visible')) {
                    $('#transaksi-container').hide();
                    isTransaksiVisible = false;
                    return;
                }

                // Tampilkan dropdown transaksi
                $('#transaksi-container').show();
                isTransaksiVisible = true;

                // Ambil data transaksi
                $.ajax({
                    url: "{{ route('transaksi.getByDate') }}",
                    method: 'POST',
                    data: {
                        date: date,
                        _token: '{{ csrf_token() }}' // Kirimkan token CSRF
                    },
                    success: function(data) {
                        let tbody = '';
                        let lastDate =
                            null; // Untuk menyimpan tanggal terakhir yang ditampilkan
                        if (data.length > 0) {
                            data.forEach(function(item, index) {
                                const currentDate = new Date(item.created_at)
                                    .toLocaleDateString('id-ID');

                                // Cek apakah tanggal saat ini sama dengan tanggal terakhir
                                if (currentDate !== lastDate) {
                                    tbody += `
                                        <tr>
                                            <td>${currentDate}</td> <!-- Tampilkan tanggal hanya jika berbeda -->
                                            <td>${index + 1}</td>
                                            <td>${item.obat.nama_obat}</td>
                                            <td>${item.obat.dosis}</td>
                                            <td>${item.obat.jenis}</td>
                                            <td>${item.jumlah}</td>
                                            <td>${Number(item.obat.harga).toLocaleString('id-ID', { minimumFractionDigits: 2 })}</td>
                                            <td>${Number(item.total).toLocaleString('id-ID')}</td>
                                            <td>${item.user.nama_pegawai}</td>
                                            <td>${item.user.ruangan}</td>
                                            <td>${new Date(item.created_at).toLocaleString('id-ID')}</td>
                                            <td>${item.status}</td>
                                            <td>
                                                <form action="{{ route('transaksi.approve', '') }}/${item.id}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm">Setujui</button>
                                                </form>
                                                <form action="{{ route('transaksi.reject', '') }}/${item.id}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                                </form>
                                            </td>
                                        </tr>
                                    `;
                                    lastDate =
                                        currentDate; // Update tanggal terakhir yang ditampilkan
                                } else {
                                    tbody += `
                                        <tr>
                                            <td></td> <!-- Tampilkan kosong jika tanggal sama -->
                                            <td>${index + 1}</td>
                                            <td>${item.obat.nama_obat}</td>
                                            <td>${item.obat.dosis}</td>
                                            <td>${item.obat.jenis}</td>
                                            <td>${item.jumlah}</td>
                                            <td>${Number(item.obat.harga).toLocaleString('id-ID', { minimumFractionDigits: 2 })}</td>
                                            <td>${Number(item.total).toLocaleString('id-ID', { minimumFractionDigits: 2 })}</td>
                                            <td>${item.user.nama_pegawai}</td>
                                            <td>${item.user.ruangan}</td>
                                            <td>${new Date(item.created_at).toLocaleString('id-ID')}</td>
                                            <td>${item.status}</td>
                                            <td>
                                                <form action="{{ route('transaksi.approve', '') }}/${item.id}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm">Setujui</button>
                                                </form>
                                                <form action="{{ route('transaksi.reject', '') }}/${item.id}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                                </form>
                                            </td>
                                        </tr>
                                    `;
                                }
                            });
                        } else {
                            tbody =
                                '<tr><td colspan="13">Tidak ada transaksi untuk tanggal ini.</td></tr>';
                        }
                        $('#transaksi-body').html(tbody);
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat mengambil data transaksi.');
                    }
                });
            });
        });

        $(document).ready(function() {
            let transactionId; // Variabel untuk menyimpan ID transaksi

            // Klik tombol Setujui
            $('.approve-btn').on('click', function() {
                transactionId = $(this).data('id'); // Ambil ID transaksi
                $('#confirmApproveModal').modal('show'); // Tampilkan modal konfirmasi setujui
            });

            // Klik tombol Tolak
            $('.reject-btn').on('click', function() {
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
