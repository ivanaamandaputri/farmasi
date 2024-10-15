@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <h4 class="card-title">Edit Transaksi</h4>

        <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                @if ($errors->has('jumlah'))
                    <div class="alert alert-danger">
                        {{ $errors->first('jumlah') }}
                    </div>
                @endif
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="obat_id">Nama Obat</label>
                        <select name="obat_id" class="form-control" required id="obat_id">
                            @foreach ($obat as $item)
                                <option value="{{ $item->id }}" data-harga="{{ $item->harga }}"
                                    data-dosis="{{ $item->dosis }}" data-jenis="{{ $item->jenis }}"
                                    data-stok="{{ $item->stok }}"
                                    {{ $item->id == $transaksi->obat_id ? 'selected' : '' }}>
                                    {{ $item->nama_obat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dosis">Dosis</label>
                        <input type="text" name="dosis" class="form-control" id="dosis" readonly required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <input type="text" name="jenis" class="form-control" id="jenis" readonly required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="text" name="stok" class="form-control" id="stok" readonly required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" value="{{ $transaksi->jumlah }}" required
                            min="1" id="jumlah">
                        @if ($errors->has('jumlah'))
                            <div class="text-danger">
                                {{ $errors->first('jumlah') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="text" name="harga" class="form-control" id="harga" readonly required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="total">Total (Rp)</label>
                        <input type="text" name="total" class="form-control" value="{{ $transaksi->total }}" readonly
                            id="total">
                    </div>
                </div>
            </div>
            <div class="form-group justify-content-start">
                <button type="submit" class="btn btn-primary">Update Transaksi</button>
                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const obatSelect = document.getElementById('obat_id');
            const jumlahInput = document.getElementById('jumlah');
            const totalInput = document.getElementById('total');
            const dosisInput = document.getElementById('dosis');
            const jenisInput = document.getElementById('jenis');
            const stokInput = document.getElementById('stok');
            const hargaInput = document.getElementById('harga');

            // Fungsi untuk memperbarui informasi obat
            const updateFields = () => {
                const selectedOption = obatSelect.options[obatSelect.selectedIndex];
                const harga = parseFloat(selectedOption.dataset.harga);
                const dosis = selectedOption.dataset.dosis;
                const jenis = selectedOption.dataset.jenis;
                const stok = selectedOption.dataset.stok;
                const jumlah = parseInt(jumlahInput.value) || 0;

                // Update input fields
                dosisInput.value = dosis;
                jenisInput.value = jenis;
                stokInput.value = stok;
                hargaInput.value = harga;

                // Update total
                totalInput.value = (harga * jumlah).toFixed(2); // Format total dengan 2 desimal
            };

            // Event listener untuk perubahan pada jumlah
            jumlahInput.addEventListener('input', () => {
                const selectedOption = obatSelect.options[obatSelect.selectedIndex];
                const harga = parseFloat(selectedOption.dataset.harga);
                const jumlah = parseInt(jumlahInput.value) || 0;

                // Update total
                totalInput.value = (harga * jumlah).toFixed(2);
            });

            // Event listener untuk perubahan pada obat
            obatSelect.addEventListener('change', updateFields);

            // Set initial values when page loads
            updateFields(); // This will set the initial values on page load
        });
    </script>
@endsection
